<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
		<title>Hệ thống hỗ trợ học chữ cái cho học sinh mẫu giáo</title>
		<link rel="stylesheet" type="text/css" href="css/style.css" />
	</head>
	<body>
		<script src="js/three.min.js"></script>
		<script src="js/libs/tween.min.js"></script>
		<script src="js/controls/TrackballControls.js"></script>
		<script src="js/renderers/CSS3DRenderer.js"></script>

		<div id="container"></div>
		<div id="info"></div>
		<div id="menu">
			<button id="table">TABLE</button>
			<button id="sphere">SPHERE</button>
			<button id="helix">HELIX</button>
			<button id="grid">GRID</button>
		</div>

		<script>

			var table = [
				"A", "Con gà", "a", 1, 1,
				"Ă", "Mặt trăng", "ă", 2, 1,
				"Â", "Cái ấm", "â", 3, 1,
				"B", "Con bướm", "b", 4, 1,
				"C", "c_bg.png", "c", 5, 1,
				"D", "Quả dứa", "d", 6, 1,
				"Đ", "Xe đạp", "đ", 7, 1,
				"E", "Con mèo", "e", 8, 1,
				"Ê", "Con dê", "ê", 9, 1,
				"G", "Chiếc ghế", "g", 10, 1,
				"H", "Bông hoa", "h", 1, 2,
				"I", "Cái kính", "i", 2, 2,
				"K", "Cái kìm", "k", 3, 2,
				"L", "Bàn là", "l", 4, 2,
				"M", "Cái mũ", "m", 5, 2,
				"N", "Cây nến", "n", 6, 2,
				"O", "Con ong", "o", 7, 2,
				"Ô", "Cái ô", "ô", 8, 2,
				"Ơ", "Quả mơ", "ơ", 9, 2,
				"P", "Đèn pin", "p", 10, 2,
				"Q", "Cái quạt", "q", 1, 3,
				"R", "Cà rốt", "r", 2, 3,
				"S", "Ốc sên", "s", 3, 3,
				"T", "Ti vi", "t", 4, 3,
				"U", "Tu hú", "u", 5, 3,
				"Ư", "Cái cưa", "ư", 6, 3,
				"V", "Con vẹt", "v", 7, 3,
				"X", "Làm xiếc", "x", 8, 3,
				"Y", "Cái yếm", "y", 9, 3
			];

			var camera, scene, renderer;
			var controls;

			var objects = [];
			var targets = { table: [], sphere: [], helix: [], grid: [] };

			init();
			animate();

			function init() {

				camera = new THREE.PerspectiveCamera( 40, window.innerWidth / window.innerHeight, 1, 10000 );
				camera.position.z = 2400;

				scene = new THREE.Scene();

				// table Shape
				for ( var i = 0; i < table.length; i += 5 ) {

					var element = document.createElement( 'div' );
					element.className = 'element';
					// element.style.backgroundColor = 'rgba(0,127,127,' + ( Math.random() * 0.5 + 0.25 ) + ')';					
					element.style.background = "url('images/"+ table[ i + 1 ] +"') rgba(0,127,127, 0.6)";

					var number = document.createElement( 'div' );
					number.className = 'number';
					number.textContent = table[ i + 2 ];
					element.appendChild( number );

					var symbol = document.createElement( 'div' );
					symbol.className = 'symbol';
					symbol.textContent = table[ i ];
					element.appendChild( symbol );

					/* var details = document.createElement( 'div' );
					details.className = 'details';
					details.innerHTML = table[ i + 1 ];
					element.appendChild( details ); */

					var object = new THREE.CSS3DObject( element );
					object.position.x = Math.random() * 4000 - 2000;
					object.position.y = Math.random() * 4000 - 2000;
					object.position.z = Math.random() * 4000 - 2000;
					scene.add( object );

					objects.push( object );

					//

					var object = new THREE.Object3D();
					object.position.x = ( table[ i + 3 ] * 330 ) - 1830;
					object.position.y = - ( table[ i + 4 ] * 460 ) + 1000;

					targets.table.push( object );

				}

				// sphere

				var vector = new THREE.Vector3();

				for ( var i = 0, l = objects.length; i < l; i ++ ) {

					var phi = Math.acos( -1 + ( 2 * i ) / l );
					var theta = Math.sqrt( l * Math.PI ) * phi;

					var object = new THREE.Object3D();

					object.position.x = 800 * Math.cos( theta ) * Math.sin( phi );
					object.position.y = 800 * Math.sin( theta ) * Math.sin( phi );
					object.position.z = 800 * Math.cos( phi );

					vector.copy( object.position ).multiplyScalar( 2 );

					object.lookAt( vector );

					targets.sphere.push( object );

				}

				// helix

				var vector = new THREE.Vector3();

				for ( var i = 0, l = objects.length; i < l; i ++ ) {

					var phi = i * 0.375 + Math.PI;

					var object = new THREE.Object3D();

					object.position.x = 900 * Math.sin( phi );
					object.position.y = - ( i * 8 ) + 350;
					object.position.z = 900 * Math.cos( phi );

					vector.x = object.position.x * 5;
					vector.y = object.position.y * 8;
					vector.z = object.position.z * 5;

					object.lookAt( vector );

					targets.helix.push( object );

				}

				// grid

				for ( var i = 0; i < objects.length; i ++ ) {

					var object = new THREE.Object3D();

					object.position.x = ( ( i % 5 ) * 400 ) - 800;
					object.position.y = ( - ( Math.floor( i / 5 ) % 5 ) * 400 ) + 800;
					object.position.z = ( Math.floor( i / 25 ) ) * 1000 - 2000;

					targets.grid.push( object );

				}

				//

				renderer = new THREE.CSS3DRenderer();
				renderer.setSize( window.innerWidth, window.innerHeight );
				renderer.domElement.style.position = 'absolute';
				document.getElementById( 'container' ).appendChild( renderer.domElement );

				//

				controls = new THREE.TrackballControls( camera, renderer.domElement );
				controls.rotateSpeed = 0.5;
				controls.minDistance = 500;
				controls.maxDistance = 6000;
				controls.addEventListener( 'change', render );

				var button = document.getElementById( 'table' );
				button.addEventListener( 'click', function ( event ) {

					transform( targets.table, 2000 );

				}, false );

				var button = document.getElementById( 'sphere' );
				button.addEventListener( 'click', function ( event ) {

					transform( targets.sphere, 2000 );

				}, false );

				var button = document.getElementById( 'helix' );
				button.addEventListener( 'click', function ( event ) {

					transform( targets.helix, 2000 );

				}, false );

				var button = document.getElementById( 'grid' );
				button.addEventListener( 'click', function ( event ) {

					transform( targets.grid, 2000 );

				}, false );

				transform( targets.table, 5000 );

				//

				window.addEventListener( 'resize', onWindowResize, false );

			}

			function transform( targets, duration ) {

				TWEEN.removeAll();

				for ( var i = 0; i < objects.length; i ++ ) {

					var object = objects[ i ];
					var target = targets[ i ];

					new TWEEN.Tween( object.position )
						.to( { x: target.position.x, y: target.position.y, z: target.position.z }, Math.random() * duration + duration )
						.easing( TWEEN.Easing.Exponential.InOut )
						.start();

					new TWEEN.Tween( object.rotation )
						.to( { x: target.rotation.x, y: target.rotation.y, z: target.rotation.z }, Math.random() * duration + duration )
						.easing( TWEEN.Easing.Exponential.InOut )
						.start();

				}

				new TWEEN.Tween( this )
					.to( {}, duration * 2 )
					.onUpdate( render )
					.start();

			}

			function onWindowResize() {

				camera.aspect = window.innerWidth / window.innerHeight;
				camera.updateProjectionMatrix();

				renderer.setSize( window.innerWidth, window.innerHeight );

				render();

			}

			function animate() {

				requestAnimationFrame( animate );

				TWEEN.update();

				controls.update();

			}

			function render() {

				renderer.render( scene, camera );

			}

		</script>
	</body>
</html>