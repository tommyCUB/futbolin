// JavaScript Document

var firebaseConfig = {
    apiKey: "AIzaSyAafjx6JcDmnqt5JI7pUMhoDNhLlg9IWto",
    authDomain: "futbolin-1562713496752.firebaseapp.com",
    databaseURL: "https://futbolin-1562713496752.firebaseio.com",
    projectId: "futbolin-1562713496752",
    storageBucket: "",
    messagingSenderId: "819952676287",
    appId: "1:819952676287:web:3f14baf3801f51a2"
  };
  // Initialize Firebase
  firebase.initializeApp(firebaseConfig);

function signGoogle()
{
	// Your web app's Firebase configuration		
	var provider = new firebase.auth.GoogleAuthProvider();
	firebase.auth().languageCode = 'es';
	provider.addScope('profile');
	provider.addScope('email');
	
			
	firebase.auth().signInWithPopup(provider).then(function(result) {
	  // This gives you a Google Access Token. You can use it to access the Google API.
	  var token = result.credential.accessToken;
	  var otoken = result.credential.id_token;
	  // The signed-in user info.
	  var user = result.user;
		
		firebase.auth().currentUser.getIdToken(/* forceRefresh */ true).then(function(idToken) {
			  // Send token to your backend via HTTPS
			  // ...
			  $.post( "utils/register.php",{token:idToken}, 
				function(data){	
					if(data!=-1){		
					  window.location.replace("es/user.php"); 	
					 }else	
						window.location.replace("index.php"); 					
		});		
		 
			}).catch(function(error) {
			  // Handle error
			});  
	  // ...
	}).catch(function(error) {
	  // Handle Errors here.
	  var errorCode = error.code;
	  var errorMessage = error.message;
	  // The email of the user's account used.
	  var email = error.email;
	  // The firebase.auth.AuthCredential type that was used.
	  var credential = error.credential;
	  // ...
		if(errorCode==='auth/account-exist-with-different-credential')
			{
				alert("Usted esta logueado con una cuenta registrada con el mismo correo.");
			}
	});
	
}

function signFacebook()
{
	var provider = new firebase.auth.FacebookAuthProvider();	
	provider.setCustomParameters({
		  'display': 'popup'
		});	
	firebase.auth().signInWithPopup(provider).then(function(result) {
	  // This gives you a Google Access Token. You can use it to access the Google API.
	  var token = result.credential.accessToken;
	  // The signed-in user info.
	  var user = result.user;
	  console.log(user);

	  firebase.auth().currentUser.getIdToken(/* forceRefresh */ true).then(function(idToken) {
			  // Send token to your backend via HTTPS
			  // ...
		       //window.location.replace("prueba.php?token="+idToken); 
		  
			   $.post( "utils/register.php",{token:idToken}, function( data )
				{
					if(data!=-1)		
					  window.location.replace("es/user.php"); 	
					 else	
						window.location.replace("index.php"); 				 
				});
			}).catch(function(error) {
			  // Handle error
			});		
	  
	  // ...
	}).catch(function(error) {
	  // Handle Errors here.
	  var errorCode = error.code;
	  var errorMessage = error.message;
	  // The email of the user's account used.
	  var email = error.email;
	  // The firebase.auth.AuthCredential type that was used.
	  var credential = error.credential;
	  // ...
		if(errorCode==='auth/account-exist-with-different-credential')
			{
				alert("Usted esta logueado con una cuenta registrada con el mismo correo.");
			}
	});	
}

function signTwitter()
{
	var provider = new firebase.auth.TwitterAuthProvider();	
	
	firebase.auth().signInWithPopup(provider).then(function(result) {
	  // This gives you a Google Access Token. You can use it to access the Google API.
	  var token = result.credential.accessToken;
	  // The signed-in user info.
	  var user = result.user;
	  console.log(user);
	  
	  $.post( "register.php",{email:user.email,name:user.displayName,origin:'TWITTER'}, function( data ){
		    if(data){				
              window.location.replace("user.php"); 		  	
			}
		});
	  // ...
	}).catch(function(error) {
	  // Handle Errors here.
	  var errorCode = error.code;
	  var errorMessage = error.message;
	  // The email of the user's account used.
	  var email = error.email;
	  // The firebase.auth.AuthCredential type that was used.
	  var credential = error.credential;
	  // ...
		if(errorCode==='auth/account-exist-with-different-credential')
			{
				alert("Usted esta logueado con una cuenta registrada con el mismo correo.");
			}
	});	
}

function signGitHub()
{
	var provider = new firebase.auth.GithubAuthProvider();	
	
	firebase.auth().signInWithPopup(provider).then(function(result) {
	  // This gives you a Google Access Token. You can use it to access the Google API.
	  var token = result.credential.accessToken;
	  // The signed-in user info.
	  var user = result.user;
	  console.log(user);
	   	
	  $.post( "register.php",{email:user.email,name:user.displayName,origin:'GITHUB'}, function( data ){
		    if(data){				
              window.location.replace("user.php"); 		  	
			}
		});
	  // ...
	}).catch(function(error) {
	  // Handle Errors here.
	  var errorCode = error.code;
	  var errorMessage = error.message;
	  // The email of the user's account used.
	  var email = error.email;
	  // The firebase.auth.AuthCredential type that was used.
	  var credential = error.credential;
	  // ...
		if(errorCode==='auth/account-exist-with-different-credential')
			{
				alert("Usted esta logueado con una cuenta registrada con el mismo correo.");
			}
	});	
}

function cerrarSesion()
	{			
	    alert("anja");
		firebase.auth().signOut().then(function() {
			window.location.replace("../utils/cerrar.php");
		}).catch(function(error) 
		{
		  // An error happened.
		});
	}

firebase.auth().onAuthStateChanged(function(user) {
        if (user) {
          // User is signed in.
          var displayName = user.displayName;
          var email = user.email;
          var emailVerified = user.emailVerified;
          var photoURL = user.photoURL;
          var isAnonymous = user.isAnonymous;
          var uid = user.uid;
          var providerData = user.providerData;
          // [START_EXCLUDE]
		   			
          // [END_EXCLUDE]
        } else {
          // User is signed out.
          // [START_EXCLUDE]					 
			/*var actual = String(window.location);
			
			if(actual.indexOf("index.php")==-1)
				window.location.replace("utils/cerrar.php");*/
			
          // [END_EXCLUDE]
        }       
     
      });

function irMarket(idTeam)
{
	window.location.replace("market.php?i="+idTeam);
}

function irUser()
{
	window.location.replace("user.php");
}

	
	
	
function cerrarSesion()
	{			
		firebase.auth().signOut().then(function() {
			window.location.replace("utils/cerrar.php");
		}).catch(function(error) 
		{
		  // An error happened.
		});
	}