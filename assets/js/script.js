$(document).ready(function () {
	var app = $.spapp({
		defaultView: "view_home",
		templateDir: "assets/views/",
	});
	console.log("App initialized with template directory set to views");

	app.route({
		view: "view_home",
		load: "home.html",
		onReady: function () {
			console.log("Home is loaded!");
		},
	});

	app.route({
		view: "view_profile",
		load: "profile.html",
	});

	app.route({
		view: "view_login",
		load: "login.html",
	});

	app.route({
		view: "view_signup",
		load: "signup.html",
	});
    app.route({
        view: "view_article",
        load: "article.html",
    });
    

	app.run();
});
