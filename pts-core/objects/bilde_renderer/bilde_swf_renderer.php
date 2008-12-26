<?php

/*
	Phoronix Test Suite
	URLs: http://www.phoronix.com, http://www.phoronix-test-suite.com/
	Copyright (C) 2008, Phoronix Media
	Copyright (C) 2008, Michael Larabel
	bilde_swf_renderer: The SWF (Flash) rendering implementation for bilde_renderer.

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

class bilde_swf_renderer extends bilde_renderer
{
	var $swf_font = null;

	public function __construct($width, $height, $embed_identifiers = "")
	{
		$this->image = new SWFMovie();
		$this->image_width = $width;
		$this->image_height = $height;
		$this->image->setDimension($width, $height);

		$this->swf_font = new SWFFont("_sans"); // TODO: Implement better font support
	}
	public function render_image($output_file = null, $quality = 0)
	{
		return $this->image->save($output_file);
	}
	public function resize_image($width, $height)
	{
		$this->image_width = $width;
		$this->image_height = $height;
		$this->image->setDimension($width, $height);
	}
	public function destroy_image()
	{
		$this->image = null;
	}

	public function write_text_left($text_string, $font_type, $font_size, $font_color, $bound_x1, $bound_y1, $bound_x2, $bound_y2, $rotate_text = false)
	{
		// TODO: Implement $font_type, $rotate_text support
		$t = new SWFTextField();
		$t->setFont($this->swf_font);
		$t->setColor($font_color[0], $font_color[1], $font_color[2]);
		$t->setHeight($font_size);
		$t->setbounds(abs($bound_x1 - $bound_x2), abs($bound_y1 - $bound_y2));
		$t->addString($text_string);

		$added = $this->image->add($t);
		$added->moveTo($bound_x1, $bound_y1);
	}
	public function write_text_right($text_string, $font_type, $font_size, $font_color, $bound_x1, $bound_y1, $bound_x2, $bound_y2, $rotate_text = false)
	{
		// TODO: Properly implement
		$this->write_text_left($text_string, $font_type, $font_size, $font_color, $bound_x1, $bound_y1, $bound_x2, $bound_y2, $rotate_text);
	}
	public function write_text_center($text_string, $font_type, $font_size, $font_color, $bound_x1, $bound_y1, $bound_x2, $bound_y2, $rotate_text = false)
	{
		// TODO: Properly implement
		$this->write_text_left($text_string, $font_type, $font_size, $font_color, $bound_x1, $bound_y1, $bound_x2, $bound_y2, $rotate_text);
	}

	public function draw_rectangle($x1, $y1, $width, $height, $background_color)
	{
		$rect = new SWFShape();
		$rect->setLine(1, $background_color[0], $background_color[1], $background_color[2]);
		$rect->setRightFill($background_color[0], $background_color[1], $background_color[2]);
		$rect->movePenTo($x1, $y1);
		$rect->drawLineTo($x1 + $width, $y1);
		$rect->drawLineTo($x1 + $width, $y1 + $height);
		$rect->drawLineTo($x1, $y1 + $height);
		$rect->drawLineTo($x1, $y1);
		$this->image->add($rect);
	}
	public function draw_rectangle_border($x1, $y1, $width, $height, $border_color)
	{
		$this->draw_line($x1, $y1, $x1 + $width, $y1, $border_color, 1);
		$this->draw_line($x1, $y1, $x1, $y1 + $height, $border_color, 1);
		$this->draw_line($x1 + $width, $y1, $x1 + $width, $y1 + $height, $border_color, 1);
		$this->draw_line($x1, $y1 + $height, $x1 + $width, $y1 + $height, $border_color, 1);
	}
	public function draw_polygon($points, $body_color, $border_color = null, $border_width = 0)
	{
		return; //TODO: Implement
	}
	public function draw_ellipse($center_x, $center_y, $width, $height, $body_color, $border_color = null, $border_width = 0)
	{
		return; //TODO: Implement
	}
	public function draw_line($start_x, $start_y, $end_x, $end_y, $color, $line_width = 1)
	{
		$line = new SWFShape();
		$line->setLine(1, $color[0], $color[1], $color[2]);
		$line->movePenTo($start_x, $start_y);
		$line->drawLine(abs($start_x - $end_x), abs($start_y - $end_y));
		$added = $this->image->add($line);
	}

	public function png_image_to_type($file)
	{
		return false; //TODO: Implement
	}
	public function jpg_image_to_type($file)
	{
		return false; //TODO: Implement
	}
	public function image_copy_merge($source_image_object, $to_x, $to_y, $source_x = 0, $source_y = 0, $width = -1, $height = -1)
	{
		return null; //TODO: Implement
	}
	public function convert_hex_to_type($hex)
	{
		return array(hexdec(substr($hex, 1, 2)), hexdec(substr($hex, 3, 2)), hexdec(substr($hex, 5, 2)));
	}
	public function text_string_dimensions($string, $font_type, $font_size, $predefined_string = false)
	{
		return array(0, 0);
	}
}

?>
