<html>
{{ HTML::style(asset("css/foundation.css")); }}
{{ HTML::style(asset("css/style.css")); }}
<title>
Login - Gallery | 91Mobiles.com
</title>

<body>
<?php echo Form::open(array('url' => '/login', 'class' => 'box login')); ?>  
<div class="dropzone-wrapper large-6" style="">
<div class="row boxBody">
    <div class="large-12 columns">
        <h4>Gallery Login</h4>
  <label>Username</label>

        <input type="text" name="username" placeholder="Enter username" />
    </div>
  </div>
  <div class="row boxBody">
    <div class="large-12 columns">
  <label>Password</label>
        <input type="password" name="password" placeholder="Enter password" />
    </div>
  </div>
  <div class="row boxBody">
    <div class="large-12 columns">
        <input type="checkbox" id="remember" name="remember" />
        <label for="remember" >Remember me</label>
    </div>
  </div>

  <div class="row boxBody" style="text-align:center;">
<div class="large-12 columns">
 <input type="submit" class="tiny button" value="Login" tabindex="4"> 
</div>
 </div>
</div>
<?php echo Form::close(); ?>

</body>
</html>
