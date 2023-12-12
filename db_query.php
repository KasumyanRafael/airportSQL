<!DOCTYPE html>
<html lang="ru">
   <head>
      <meta charset="UTF-8">
      <title>Запросы к БД</title>
	  <script src="jquery-3.6.4.min.js"></script>
   </head>
   <body>
		<div id="content">
			<div id="left_side">
			</div>

			<div id="top_side">
			</div>

			<div id="center_side">
			</div>
			
			<div id="bottom_side">
			</div>
		</div>
	  <script>
		$(document).ready(function(){
			$.ajax({
				url:'get_db_list.php',
				type:'POST',
				data:{user:'user'},
				dataType: "html"
			}).done(function(data){
				$("#left_side").html(data)
				console.log(data)
			}).fail(function(e){
				console.log(e)
			})
		})
	  </script>		
   </body>
</html>