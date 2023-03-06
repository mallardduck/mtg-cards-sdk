<?php

namespace MallardDuck\MtgCardsSdk\Generator\HooksEmitter;

final class HooksEmitter
{
    /**
     * Kinda like $wp_filter global...rename to eventList
     */
    private array $filters = [];
    private array $mergedFilters = [];
    /**
     * Kinda like $wp_actions global...rename to events
     * @var int[]
     */
    private array $actions = [];
    /**
     * Kinda like $wp_current_filter global...
     * @var string[]
     */
    private array $currentFilter = [];

    private static HooksEmitter $instance;

    private function __construct() {}

    public static function getInstance() {
        if (!isset(self::$instance)) {
            self::$instance = new HooksEmitter();
            self::$instance->doAction(Events::AfterEmitterSetup->eventSuffixedKey(), self::$instance);
        }
        return self::$instance;
    }

    public function addFilter($tag, $functionToAdd, $priority = 10, $acceptedArgs = 1)
    {
        $idx = $this->filterBuildUniqueId($tag, $functionToAdd, $priority);
        $this->filters[$tag][$priority][$idx] = [
            'function' => $functionToAdd,
            'accepted_args' => $acceptedArgs
        ];
        unset($this->mergedFilters[$tag]);
        return true;
    }

    public function removeFilter($tag, $functionToRemove, $priority = 10)
    {
        $functionToRemove = $this->filterBuildUniqueId($tag, $functionToRemove, $priority);

        $results = isset($this->filters[$tag][$priority][$functionToRemove]);

        if (true === $results) {
            unset($this->filters[$tag][$priority][$functionToRemove]);
            if (empty($this->filters[$tag][$priority])) {
                unset($this->filters[$tag][$priority]);
            }
            unset($this->mergedFilters[$tag]);
        }
        return $results;
    }

    public function removeAllFilters($tag, $priority = false)
    {
        if (isset($this->filters[$tag])) {
            if (false !== $priority && isset($this->filters[$tag][$priority])) {
                unset($this->filters[$tag][$priority]);
            } else {
                unset($this->filters[$tag]);
            }
        }

        if (isset($this->mergedFilters[$tag])) {
            unset($this->mergedFilters[$tag]);
        }

        return true;
    }

    public function hasFilter($tag, $functionToCheck = false)
    {
        $has = !empty($this->filters[$tag]);
        if (false === $functionToCheck || false == $has) {
            return $has;
        }

        if (!$idx = $this->filterBuildUniqueId($tag, $functionToCheck, false)) {
            return false;
        }

        foreach ((array)array_keys($this->filters[$tag]) as $priority) {
            if (isset($this->filters[$tag][$priority][$idx])) {
                return $priority;
            }
        }
        return false;
    }

    public function applyFilters($tag, $value) {
        $args = array();
        // Do 'all' actions first
        if ( isset($this->filters['all']) ) {
            $this->currentFilter[] = $tag;
            $args = func_get_args();
            $this->callAllHook($args);
        }

        if ( !isset($this->filters[$tag]) ) {
            if ( isset($this->filters['all']) )
                array_pop($this->currentFilter);
            return $value;
        }

        if ( !isset($this->filters['all']) )
            $this->currentFilter[] = $tag;

        // Sort
        if ( !isset( $this->mergedFilters[ $tag ] ) ) {
            ksort($this->filters[$tag]);
            $this->mergedFilters[ $tag ] = true;
        }

        reset( $this->filters[ $tag ] );

        if ( empty($args) )
            $args = func_get_args();

        do {
            foreach( (array) current($this->filters[$tag]) as $the_ )
                if ( !is_null($the_['function']) ){
                    $args[1] = $value;
                    $value = call_user_func_array($the_['function'], array_slice($args, 1, (int) $the_['accepted_args']));
                }

        } while ( next($this->filters[$tag]) !== false );

        array_pop( $this->currentFilter );

        return $value;
    }

    public function applyFiltersRefArray($tag, $args)
    {
        // Do 'all' actions first
        if (isset($this->filters['all'])) {
            $this->currentFilter[] = $tag;
            $all_args = func_get_args();
            $this->callAllHook($all_args);
        }

        if (!isset($this->filters[$tag])) {
            if (isset($this->filters['all'])) {
                array_pop($this->currentFilter);
            }
            return $args[0];
        }

        if (!isset($this->filters['all'])) {
            $this->currentFilter[] = $tag;
        }

        // Sort
        if (!isset($this->mergedFilters[$tag])) {
            ksort($this->filters[$tag]);
            $this->mergedFilters[$tag] = true;
        }

        reset($this->filters[$tag]);

        do {
            foreach ((array)current($this->filters[$tag]) as $the_) {
                if (!is_null($the_['function'])) {
                    $args[0] = call_user_func_array($the_['function'], array_slice($args, 0, (int)$the_['accepted_args']));
                }
            }
        } while (next($this->filters[$tag]) !== false);

        array_pop($this->currentFilter);

        return $args[0];
    }

    public function addAction($tag, $function_to_add, $priority = 10, $accepted_args = 1) {
        return $this->addFilter($tag, $function_to_add, $priority, $accepted_args);
    }

    public function hasAction($tag, $function_to_check = false) {
        return $this->hasFilter($tag, $function_to_check);
    }

    public function removeAction( $tag, $function_to_remove, $priority = 10 ) {
        return $this->removeFilter( $tag, $function_to_remove, $priority );
    }

    public function removeAllActions($tag, $priority = false) {
        return $this->removeAllFilters($tag, $priority);
    }

    public function doAction($tag, $arg = '')
    {
        if (!isset($this->actions)) {
            $this->actions = array();
        }

        if (!isset($this->actions[$tag])) {
            $this->actions[$tag] = 1;
        } else {
            ++$this->actions[$tag];
        }

        // Do 'all' actions first
        if (isset($this->filters['all'])) {
            $this->current_filter[] = $tag;
            $all_args = func_get_args();
            $this->callAllHook($all_args);
        }

        if (!isset($this->filters[$tag])) {
            if (isset($this->filters['all'])) {
                array_pop($this->currentFilter);
            }
            return;
        }

        if (!isset($this->filters['all'])) {
            $this->currentFilter[] = $tag;
        }

        $args = array();
        if (is_array($arg) && 1 == count($arg) && isset($arg[0]) && is_object($arg[0])) { // array(&$this)
            $args[] =& $arg[0];
        } else {
            $args[] = $arg;
        }
        for ($a = 2; $a < func_num_args(); $a++) {
            $args[] = func_get_arg($a);
        }

        // Sort
        if (!isset($this->mergedFilters[$tag])) {
            ksort($this->filters[$tag]);
            $this->mergedFilters[$tag] = true;
        }

        reset($this->filters[$tag]);

        do {
            foreach ((array) current($this->filters[$tag]) as $the_) {
                if (!is_null($the_['function'])) {
                    call_user_func_array($the_['function'], array_slice($args, 0, (int) $the_['accepted_args']));
                }
            }
        } while (next($this->filters[$tag]) !== false);

        array_pop($this->currentFilter);
    }

    public function doActionRefArray($tag, $args)
    {
        if (!isset($this->actions)) {
            $this->actions = array();
        }

        if (!isset($this->actions[$tag])) {
            $this->actions[$tag] = 1;
        } else {
            ++$this->actions[$tag];
        }

        // Do 'all' actions first
        if (isset($this->filters['all'])) {
            $this->currentFilter[] = $tag;
            $allArgs = func_get_args();
            $this->callAllHook($allArgs);
        }

        if (!isset($this->filters[$tag])) {
            if (isset($this->filters['all'])) {
                array_pop($this->currentFilter);
            }
            return;
        }

        if (!isset($this->filters['all'])) {
            $this->currentFilter[] = $tag;
        }

        // Sort
        if (!isset($mergedFilters[$tag])) {
            ksort($this->filters[$tag]);
            $mergedFilters[$tag] = true;
        }

        reset($this->filters[$tag]);

        do {
            foreach ((array)current($this->filters[$tag]) as $the_) {
                if (!is_null($the_['function'])) {
                    call_user_func_array(
                        $the_['function'],
                        array_slice($args, 0, (int)$the_['accepted_args'])
                    );
                }
            }
        } while (next($this->filters[$tag]) !== false);

        array_pop($this->currentFilter);
    }

    public function didAction($tag)
    {
        if (!isset($this->actions) || !isset($this->actions[$tag])) {
            return 0;
        }

        return $this->actions[$tag];
    }

    public function currentFilter() {
        return end( $this->currentFilter );
    }

    function currentAction() {
        return $this->currentFilter();
    }

    function doingFilter( $filter = null ) {
        if ( null === $filter ) {
            return ! empty( $this->currentFilter );
        }
        return in_array( $filter, $this->currentFilter );
    }

    function doingAction( $action = null ) {
        return $this->doingFilter( $action );
    }

    private function filterBuildUniqueId($tag, $function, $priority) {
        static $filter_id_count = 0;

        if ( is_string($function) )
            return $function;

        if ( is_object($function) ) {
            // Closures are currently implemented as objects
            $function = array( $function, '' );
        } else {
            $function = (array) $function;
        }

        if (is_object($function[0]) ) {
            // Object Class Calling
            if ( function_exists('spl_object_hash') ) {
                return spl_object_hash($function[0]) . $function[1];
            } else {
                $obj_idx = get_class($function[0]).$function[1];
                if ( !isset($function[0]->filter_id) ) {
                    if ( false === $priority )
                        return false;
                    $obj_idx .= isset($this->filters[$tag][$priority]) ? count((array)$this->filters[$tag][$priority]) : $filter_id_count;
                    $function[0]->filter_id = $filter_id_count;
                    ++$filter_id_count;
                } else {
                    $obj_idx .= $function[0]->filter_id;
                }

                return $obj_idx;
            }
        } else if ( is_string($function[0]) ) {
            // Static Calling
            return $function[0].$function[1];
        }
    }

    private function callAllHook($args): void
    {
        reset( $this->filters['all'] );
        do {
            foreach( (array) current($this->filters['all']) as $the_ )
                if ( !is_null($the_['function']) )
                    call_user_func_array($the_['function'], $args);

        } while ( next($this->filters['all']) !== false );
    }
}