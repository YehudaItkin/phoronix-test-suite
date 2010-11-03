<?php

/*
	Phoronix Test Suite
	URLs: http://www.phoronix.com, http://www.phoronix-test-suite.com/
	Copyright (C) 2009 - 2010, Phoronix Media
	Copyright (C) 2009 - 2010, Michael Larabel

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation; either version 3 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program. If not, see <http://www.gnu.org/licenses/>.
*/

class finish_run implements pts_option_interface
{
	public static function argument_checks()
	{
		return array(
		new pts_argument_check(0, array("pts_result_file", "is_test_result_file"), null, "The name of a test result file must be entered.")
		);
	}
	public static function run($args)
	{
		$result_file = new pts_result_file($args[0]);

		$system_identifiers = $result_file->get_system_identifiers();
		$test_positions = array();

		$pos = 0;
		foreach($result_file->get_result_objects() as $result_object)
		{
			$this_result_object_identifiers = $result_object->test_result_buffer->get_identifiers();

			foreach($system_identifiers as $system_identifier)
			{
				if(!in_array($system_identifier, $this_result_object_identifiers))
				{
					if(!isset($test_positions[$system_identifier]))
					{
						$test_positions[$system_identifier] = array();
					}

					array_push($test_positions[$system_identifier], $pos);
				}
			}

			$pos++;
		}

		$incomplete_identifiers = array_keys($test_positions);

		if(count($incomplete_identifiers) == 0)
		{
			echo "\nIt appears that there are no incomplete test results within this saved file.\n\n";
			return false;
		}

		$selected = pts_user_io::prompt_text_menu("Select which incomplete test run you would like to finish", $incomplete_identifiers);

		// Now run it
		if(pts_test_run_manager::initial_checks($args[0]) == false)
		{
			return false;
		}

		$test_run_manager = new pts_test_run_manager(pts_c::is_recovering);

		// Load the tests to run
		if($test_run_manager->load_result_file_to_run($args[0], $selected, $result_file, $test_positions[$selected]) == false)
		{
			return false;
		}

		// Run the actual tests
		$test_run_manager->pre_execution_process();
		$test_run_manager->call_test_runs();
		$test_run_manager->post_execution_process();
	}
}

?>