<?php
function MF2D($modelField) {
	return 'data[' . implode('][', explode('.', $modelField)) . ']';
}