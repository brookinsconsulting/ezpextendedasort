<?php

class eZPExtendedAsortOperator
{
    var $Operators;
    var $funcstring_ar = array();
    var $sort_ar = array();

    function eZPExtendedAsortOperator( $name = "extended_asort" )
    {
	$this->Operators = array( $name );
    }

    function &operatorList()
    {
	return $this->Operators;
    }

    function namedParameterPerOperator()
    {
        return true;
    }   

    function namedParameterList()
    {
        return array( 'extended_asort' => array( 'first_param' => array( 'type' => 'array', 'required' => false, 'default' => array('name') ),
				                 'second_param' => array( 'type' => 'integer', 'required' => false, 'default' => 1 ),
					         'third_param' => array( 'type' => 'string', 'required' => false, 'default' => 'ASC' ) ) );
    }

    function funcstring_ar_append ($key, $addme) {
        $temp_ar = $this->funcstring_ar[$key];
        $temp_ar[] = $addme;
        $this->funcstring_ar[$key] = $temp_ar;
    }

    function build_funcstring_ar ($key, $object, $seek_value, $val_now = array()) {

        while(is_object($val_now) || is_array($val_now) ) {
            $val_now = $this->eval_funcstring_ar($key, $object);
	     if (is_object($val_now) && method_exists($val_now, $seek_value)) {
                $this->funcstring_ar_append($key, "->".$seek_value."()");
                continue;
            } elseif (is_array($val_now) && array_key_exists($seek_value, $val_now)) {
                $this->funcstring_ar_append($key, "['".$seek_value."']");
                continue;
            } elseif (method_exists($val_now, 'hasAttribute')) {
                if ($val_now->hasAttribute($seek_value)) {
                    $this->funcstring_ar_append($key, "->attribute('$seek_value')");
                    continue;
                } elseif ($val_now->hasAttribute('main_node') && get_class($val_now) == 'ezcontentobject') {
                    $this->funcstring_ar_append($key, "->attribute('main_node')");
                    continue;
                } elseif ($val_now->hasAttribute('data_map')) {
                    $this->funcstring_ar_append($key, "->attribute('data_map')");
                    continue;
                } elseif (method_exists($val_now, 'Content')) {
                    $this->funcstring_ar_append($key, "->Content()");
                    continue;
                } elseif ($val_now->hasAttribute('timestamp') && $val_now->attribute('timestamp') != 0) {
                    $this->funcstring_ar_append($key, "->attribute('timestamp')");
                    continue;
                } elseif ($val_now->hasAttribute('data_int') && $val_now->attribute('data_int') != 0) {
                    $this->funcstring_ar_append($key, "->attribute('data_int')");
                    continue;
                } elseif ($val_now->hasAttribute('data_text') && $val_now->attribute('data_text') != '') {
                    $this->funcstring_ar_append($key, "->attribute('data_text')");
                    continue;
                }
            } elseif (method_exists($val_now, 'Content')) {
                $this->funcstring_ar_append($key, "->Content()");
                continue;
            } 
            break;
        }
        if ( is_object($val_now) ) {
            return false;
        }
	 if ( is_array($val_now) ) $this->funcstring_ar_append($key, "[0]");
        return true;
    }


    function eval_funcstring_ar ($key, $object) {
        foreach ($this->funcstring_ar[$key] as $funcstring) {
            $evalme = "$"."object=$"."object".$funcstring.";";
            eval($evalme);
        }
        return $object;
    }

    function funcstring_catch_error($func_pos, $this_element) {
        $func_pos++;
        $sort_val = '';
        if ($func_pos >= count($this->funcstring_ar)) {
            if (method_exists($sort_val,'hasAttribute') && $this_element->hasAttribute('object')) {
                $this_element = $this_element->attribute('object');
                $func_pos = 0;
            } elseif (method_exists($sort_val,'hasAttribute') && $this_element->hasAttribute('main_node')) {
                $this_element = $this_element->attribute('main_node');
                $func_pos = 0;
            } else {
                $sort_val = 'Error!';
            }
        }
        return array($func_pos, $this_element, $sort_val);
    }

    function modify( $tpl, &$operatorName, &$operatorParameters, $rootNamespace, $currentNamespace, &$operatorValue, &$namedParameters )
    {

print_r( $namedParameters );
	  $this->funcstring_ar = array();
	  if (is_array($operatorValue)) {
            $sort_flag = $namedParameters['second_param'];
            $data_input = $operatorValue;
            if ($sort_flag > 3) $sort_flag = 0;
            foreach ($namedParameters['first_param'] as $key => $this_seek) {
                $this->funcstring_ar[] = array();
                if (!$this->build_funcstring_ar($key, $operatorValue[0], $this_seek) ) {
                    //eZDebug::writeDebug( 'Could not return valid function for sort index '.$this_seek);
                }
            }


	     if (in_array( $namedParameters['first_param'][0], array('priority','published','modified') ) ) $sort_flag = 1;
	     
	     foreach ($operatorValue as $key => $this_element) {

	         $func_pos = 0;
		  $sort_val = '';
		  while ($sort_val == '') {

	             if (!$sort_val = $this->eval_funcstring_ar($func_pos, $this_element)) { 
	                 list($func_pos, $this_element, $sort_val) = $this->funcstring_catch_error($func_pos, $this_element);
		      } elseif (empty($sort_val)) { 
	                 list($func_pos, $this_element, $sort_val) = $this->funcstring_catch_error($func_pos, $this_element);
		      }
		  }
		  if ($sort_val == "Error!") $sort_val = ($sort_flag == 1) ? 0 : '';
		  $this->sort_ar[$key] = ($sort_flag == 1) ? (int) $sort_val : $sort_val;
	     }

            $output_pattern = $this->sort_ar;

            asort($output_pattern, $sort_flag);

            $operatorValue = array();

            foreach ($output_pattern as $key => $this_sorted) {
		  $operatorValue[] = $data_input[$key];
            }

            if ($namedParameters['third_param'] == 'DESC') $operatorValue = array_reverse($operatorValue);

            return true;

	  } else {
            return true;
	  } 
    }

}

?>
