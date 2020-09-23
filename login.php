<html>
<head>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

    
    <!------ Include the above in your HEAD tag ---------->
    <link rel= "stylesheet" type= "text/css" href="assets/css/style.css">
<style>

/* BASIC */

</style>

</head>
<body>
    <div class="wrapper fadeInDown">
    <div id="formContent">
        <!-- Tabs Titles -->

        <!-- Icon -->
        <div class="fadeIn first">
        <img src="assets/image/rtarf.png" id="icon" alt="User Icon" />
        </div>

        <!-- Login Form -->
       
        <input type="text" id="login" class="fadeIn second" name="login" placeholder="login">
        <input type="text" id="password" class="fadeIn third" name="login" placeholder="password">
        <input type="button" class="fadeIn fourth" value="Log In" Onclick="login();">
       

    

    </div>
    </div>


</body>

<script>

        function login(){
			let user = $('#login').val();
			let pass = $('#password').val();

			$.ajax({
				url: 'check_login.php',
				type: "post",
				data: "username="+user+"&password="+pass,
				success: function (data) {
                    console.log(data);
					
					
				},	
			});
			
		}
</script>

</html>