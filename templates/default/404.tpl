<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title>404</title>
<style type="text/css">
	html, body{margin:0;}
	.demo-smile{

		width: 100%;
		height: 100vh;
		background: #65AA00;

		display: flex;
		align-items: center;
		justify-content: center;
	}
	.sad-smile{

		width: 250px;
		height: 250px;

		position: relative;
		border-radius: 50%;

		background: linear-gradient(to bottom, #efee5f, #ecc837);
		box-shadow: inset 0px -14px 14px rgba(0, 0, 0, .3), 0px 2px 20px rgba(0, 0, 0, .6);
	}
	.sad-smile__eyes{

		position: absolute;
		top: 13.3%;

		background-color: #fff;
		border: 8px solid #000;
		border-radius: 50% 50% 0 0;

		height: 20%;
		width: 20%;
	}

	.sad-smile__lefteye{
		left: 20%;
	}

	.sad-smile__righteye{
		right: 20%;
	}
	.sad-smile__eyes:after{

		content: "";
		display: block;
		background-color: #000;

		position: absolute;
		left:0;
		bottom: 0;

		width: 41.7%;
		height: 41.7%;
		border-radius: 50%;
	}
	.sad-smile__eyes:before{

		content: "";
		display: block;
		position: absolute;
		background-color: #000;

		border-radius: 100% 100% 0 0;
		height: 13.3%;
		width: 66.7%;

		right: 16.7%;
		top: -33.3%;
	}
	.sad-smile__mouth{

		width: 66.7%;
		height: 30%;

		border-bottom: 7px solid #222;
		border-right: 7px solid transparent;
		border-left: 7px solid transparent;
		border-radius: 50%;

		position: absolute;
		bottom: 5%;
		left: 50%;
		transform: rotateX(180deg) translate(-50%, 0);
	}
	@keyframes animation-tear{

		5%{
			opacity: 1;
		}

		10%{
			transform: translateZ(0) translate(0, 25px) scale(1);
		}

		100%{
			transform: translateZ(0) translate(0, 125px) scale(1);
		}

	}
	.sad-smile__tear{

		position: absolute;
		top: 50%;
		left: 0%;

		width: 0;
		height: 0;
		opacity: 0;

		border-right: 8px solid transparent;
		border-left: 8px solid transparent;
		border-bottom: 24px solid #1ca5e2;
		border-radius: 50%;

		transform: translateZ(0) translate(0, 0) scale(0);
		animation: animation-tear 2.5s cubic-bezier(0.63, 0.49, 1,-0.15) .2s infinite;
	}
	.sad-smile__righteye .sad-smile__tear{
		animation-delay: 1s;
	}
	.demo-smile__label{
		margin-left: 20px;
		font-size: 30px;
		font-family: arial;
		color: #fff;
	}
</style>
</head>
<body>
<div  style="display:none;" id="tmp_name"><template style="display:none;">404</template></div>
<script type="text/javascript">
	$("#tmp_name").hide();
</script>
<div class="demo-smile">
	<div class="sad-smile">
		<div class="sad-smile__eyes sad-smile__lefteye">
			<div class="sad-smile__tear"></div>
		</div>
		<div class="sad-smile__eyes sad-smile__righteye">
			<div class="sad-smile__tear"></div>
		</div>
		<div class="sad-smile__mouth"></div>
	</div>
	<span class="demo-smile__label"> Извините, такая страница ненайдена !</span>
</div>
</body>
</html>