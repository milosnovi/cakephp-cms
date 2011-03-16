<?php
function MF2D($modelField) {
	return 'data[' . implode('][', explode('.', $modelField)) . ']';
}

function objectsIntoArray($arrObjData, $arrSkipIndices = array())
{
    $arrData = array();
    if (is_object($arrObjData)) {
        $arrObjData = get_object_vars($arrObjData);
    }
   
    if (is_array($arrObjData)) {
        foreach ($arrObjData as $index => $value) {
            if (is_object($value) || is_array($value)) {
                $value = objectsIntoArray($value, $arrSkipIndices); // recursive call
            }
            if (in_array($index, $arrSkipIndices)) {
                continue;
            }
            $arrData[$index] = $value;
        }
    }
    return $arrData;
}

function getUrlPrefix() {
	$queryString = false;
	if (isset($_SERVER['QUERY_STRING'])) {
		$queryString = explode('url=', $_SERVER['QUERY_STRING']);
	}
	$result = '';
	if (is_array($queryString) && (2 <= count($queryString))) {
		$urlPaths = explode('/', $queryString[1]);
		if (is_array($urlPaths) && !empty($urlPaths)) {
			$result = $urlPaths[0];
		}
	}
	return $result;
}

// Returns domain in which current action is executing (ADMIN-CP or WebFront)
function isAdminDomain() {
	return getUrlPrefix() == 'admin';
}
