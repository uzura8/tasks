<?php

/**
 * Site_Upload class tests
 *
 * @group App
 * @group Model
 */
class Test_Site_Upload extends TestCase
{
	protected function setUp()
	{
	}

	public function test_check_real_file_info()
	{
		if (!$file_paths = Util_file::get_file_recursive(Config::get('site.upload.types.img.raw_file_path')))
		{
			$this->markTestSkipped('No data.');
		}

		foreach ($file_paths as $file_path)
		{
			$file_info = File::file_info($file_path);
			$file_name = $file_info['basename'];
			$file = Model_File::get4name($file_name);

			// file に対応する Model_File が存在する
			$this->assertNotEmpty($file);

			// path の確認
			$this->assertEquals(trim($file->path, '/'), Util_file::get_path_partial($file_info['dirname'], 2));

			// type の確認
			$this->assertEquals($file->type, $file_info['mimetype']);

			// size の確認
			$this->assertEquals($file->filesize, $file_info['size']);
		}
	}

	public function test_check_real_file_tmp_info()
	{
		if (!$file_paths = Util_file::get_file_recursive(Config::get('site.upload.types.img.tmp.raw_file_path')))
		{
			$this->markTestSkipped('No data.');
		}

		foreach ($file_paths as $file_path)
		{
			$file_info = File::file_info($file_path);
			$file_name = $file_info['basename'];
			$file = Model_FileTmp::get4name($file_name);

			// file に対応する Model_File が存在する
			$this->assertNotEmpty($file);

			$is_thumbnail = (Util_file::get_path_partial($file_info['dirname'], 1) == 'thumbnail');

			// path の確認
			$length = $is_thumbnail ? 3 : 2;
			$offset = $is_thumbnail ? 1 : 0;
			$this->assertEquals(trim($file->path, '/'), Util_file::get_path_partial($file_info['dirname'], $length, $offset));

			// type の確認
			$this->assertEquals($file->type, $file_info['mimetype']);

			// size の確認
			if (!$is_thumbnail) $this->assertEquals($file->filesize, $file_info['size']);
		}
	}
}
