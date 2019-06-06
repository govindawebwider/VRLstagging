<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class uploadSampleWatermarkVideo extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;
    private $inputs;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($inputs)
    {
        $this->inputs = $inputs;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        \Log::info($this->inputs);
        $ffmpegPath = '/usr/bin/ffmpeg';
        $watermark = base_path() . '/public/vrl_logo.png';
        $inputPath = base_path() . '/public/'.$this->inputs['inputPath'];
        $outPath = base_path() . '/public/'.$this->inputs['outPath'];
        $thumbnailVideo = base_path() . '/public/'.$this->inputs['thumbnailVideo'];
        shell_exec("$ffmpegPath  -i $inputPath -i $watermark -filter_complex \"[1][0]scale2ref=(262/204)*ih/12:ih/12[wm][vid];[vid][wm]overlay=x=(W-w-20):y=(H-h-20)\" $outPath ");
        shell_exec("$ffmpegPath  -i $inputPath -i $watermark  -filter_complex \"[0:v]scale=640:360[bg];[bg][1:v]overlay=x=(W-w-20):y=(H-h-20)\" $thumbnailVideo ");
        $waterMarkPath = $this->inputs['outPath'];
        $thumbnailVideoPath = $this->inputs['thumbnailVideo'];
        /*multipartUpload($thumbnailVideo);
        multipartUpload($outPath);*/
        $disk = \Illuminate\Support\Facades\Storage::disk('s3');
        $disk->put($waterMarkPath, file_get_contents($outPath), 'public');
        $disk->put($thumbnailVideoPath, file_get_contents($thumbnailVideo), 'public');
        unlink($thumbnailVideo);
        unlink($outPath);
        unlink($inputPath);
    }
}
