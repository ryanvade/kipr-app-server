<html>
	<head>
		<title> Judging Interface </title>
		<style type='text/css'>		
			#description {
				text-align: center;
				width: 50%;
				float: left;
				padding: 5px 0px 5px 0px;
			  font-size: 25px;
			}

			#scale {
				text-align: center;
			  width: 50%;
			  float: right;
				padding: 5px 0px 5px 0px;
			  font-size: 25px;
			}

			input::-webkit-outer-spin-button,
			input::-webkit-inner-spin-button {
				-webkit-appearance: none;
			  margin: 0;
			}

			input[type='number'] {
			  -moz-appearance:textfield;
			}

			</style>
	</head>
	<body>
		<section>
			<h1 align="center">Judge Scoring Interface</h1>
			<h1 align="center">Team A Scoring</h1>
		</section>
		<section id="description">
				<h1>Scoring List</h1>
				<p>Red balls in basket</p>
				<p>Blue balls in basket</p>
		</section>
		<section id="scale">
		    <h1>Scoring Values</h1>
				<section>
					<input type="number"></input>
					<label for="plus"></label>
					<input id="plus" value="+" type="button"></input>
					<label for="minus"></label>
					<input id="minus" value="-" type="button"></input>
				</section>
				<section>
					<input type="number"></input>
					<label for="plus"></label>
					<input id="plus" value="+" type="button"></input>
					<label for="minus"></label>
					<input id="minus" value="-" type="button"></input>
				</section>
		</section>
	</body>
</html>
