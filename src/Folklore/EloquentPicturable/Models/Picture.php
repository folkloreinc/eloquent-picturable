<?php namespace Folklore\EloquentPicturable\Models;

class Picture extends Model {

    protected $table = 'pictures';

    protected $guarded = array();
    protected $fillable = array(
        'picturable_order',
        'picturable_position',
        'filename',
        'original',
        'mime',
        'size',
        'width',
        'height'
    );

    public function picturable()
    {
        return $this->morphTo();
    }
    
    /*
	 *
	 * Pictures
	 *
	 */
	public static function upload($file,$params = array()) {

		//Get infos
		list($width, $height, $type, $attr) = getimagesize($file->getRealPath());
		$original = $file->getClientOriginalName();
		$mime = $file->getMimeType();
		$size = $file->getSize();

		//Create item
		$item = new self();
		$item->fill($params);
		$item->fill(array(
			'original' => $original,
			'mime' => $mime,
			'size' => $size,
			'width' => $width,
			'height' => $height
		));
		$item->save();

		//Get destination
		$extensions = config('picturable.mime_to_extension');
		$destinationPath = config('picturable.upload_path');
		$folder = date('Y-m-d');
		$extension = $extensions[$mime];
		$filename = $item->id.'.'.$extension;

		//Create directory if doesn't exist
		if(!file_exists($destinationPath.'/'.$folder)) {
			mkdir($destinationPath.'/'.$folder, 0775, true);
		}

		//Move file
		$file->move($destinationPath.'/'.$folder, $filename);

		//Fix permissions problem in local
		if(app()->environment() == 'local')
        {
			chmod($destinationPath.'/'.$folder.'/'.$filename, 0777);
		}

		//Save filename
		$item->fill(array(
			'filename' => $folder.'/'.$filename
		));
		$item->save();

		return $item;

	}


}
