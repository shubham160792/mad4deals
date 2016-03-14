<?php
$host = 'http://';
$SERVER_PATH = $host.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
?>
<?php $pathCheck = strpos($SERVER_PATH , Config::get('app.url')); ?>
<li class="<?php if($SERVER_PATH != Config::get('app.url') && $pathCheck >= 0 && $pathCheck !== false){ echo "active";   } ?>">
    <a href="<?php echo Config::get('app.url');?>">
        Create Gallery
    </a>
</li>
<select id="products">
    <option value="select">Products View</option>
    <option value="all">All Products</option>
    <option value="top200">Top 200 Products</option>
    <option value="content">Content Summary</option>
</select>
<?php $pathCheck = strpos($SERVER_PATH , Config::get('app.url')."gallery"); ?>
<li class="<?php if($SERVER_PATH != Config::get('app.url') && $pathCheck >= 0 && $pathCheck !== false){ echo "active";   } ?>">
    <a href="<?php echo Config::get('app.url')."gallery";?>">
        Manage Galleries
    </a>
</li>
<?php $pathCheck = strpos($SERVER_PATH , Config::get('app.url')."category"); ?>
<li class="<?php if($SERVER_PATH != Config::get('app.url') && $pathCheck >= 0 && $pathCheck !== false){ echo "active";   } ?>">
        <a href="<?php echo Config::get('app.url')."category";?>">
                Manage Categories
        </a>
</li>
<?php $pathCheck = strpos($SERVER_PATH , Config::get('app.url')."caption"); ?>
<li class="<?php if($SERVER_PATH != Config::get('app.url') && $pathCheck >= 0 && $pathCheck !== false){ echo "active";   } ?>">
    <a href="<?php echo Config::get('app.url')."caption";?>">
        Manage Caption
    </a>
</li>
<?php $pathCheck = strpos($SERVER_PATH , Config::get('app.url')."tag"); ?>
<li class="<?php if($SERVER_PATH != Config::get('app.url') && $pathCheck >= 0 && $pathCheck !== false){ echo "active";   } ?>">
    <a href="<?php echo Config::get('app.url')."tag";?>">
        Manage Tags
    </a>
</li>

<?php $pathCheck = strpos($SERVER_PATH , Config::get('app.url')."logout"); ?>
<li class="<?php if($SERVER_PATH != Config::get('app.url') && $pathCheck >= 0 && $pathCheck !== false){ echo "active";  } ?>">
        <a  href="{{ URL::to(Config::get('app.url_path').'logout') }}" >Logout</a>
</li>

