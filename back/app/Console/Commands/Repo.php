<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class Repo extends Command
{
    
    protected $signature = 'make:repo {name}';
    protected $description = 'Make Interface and Repo Class';
    protected $files;


    public function __construct(Filesystem $files)
    {
        $this->files=$files;
        parent::__construct();
    }


    public function handle()
    {
        $repo=$this->argument('name');

        if ($repo === '' || is_null($repo) || empty($repo)) {
            return $this->error('Repo Name Invalid..!');
        }

        
        if ($this->confirm('Do you wish to create '.$repo.' Repo?')) {
            $this->makeClass($repo);
            $this->makeInterface($repo);

        }
    } 


    protected function makeClass($repo){
        $classContents=
'<?php
        
namespace App\Http\Classes;    

class '.$repo.'Repo implements '.$repo.'Interface
{
                        
                    
    public function __construct()
    {
    }
                    
}';

        $file = "${repo}Repo.php";
        $path='App/Http/Classes';
            
        $file=$path."/$file";

        if($this->files->isDirectory($path)){
            if($this->files->isFile($file))
                return $this->error($repo.' File Already exists!');
                
            if(!$this->files->put($file, $classContents))
                return $this->error('Something went wrong!');
                $this->info("$repo Repo generated!");
        }
        else{
            $this->files->makeDirectory($path, 0777, true, true);

            if(!$this->files->put($file, $classContents))
                return $this->error('Something went wrong!');
                $this->info("$repo Repo generated!");
        }
    }



    protected function makeInterface($repo){
        $classContents=
'<?php
        
namespace App\Http\Interfaces;     
interface '.$repo.'Interface
{
                        
                    
    
                    
}';

        $file = "${repo}Interface.php";
        $path='App/Http/Interfaces';
            
        $file=$path."/$file";

        if($this->files->isDirectory($path)){
            if($this->files->isFile($file))
                return $this->error($repo.' File Already exists!');
                
            if(!$this->files->put($file, $classContents))
                return $this->error('Something went wrong!');
                $this->info("$repo Interface generated!");
        }
        else{
            $this->files->makeDirectory($path, 0777, true, true);

            if(!$this->files->put($file, $classContents))
                return $this->error('Something went wrong!');
                $this->info("$repo Interface generated!");
        }
    }
    


    
}
