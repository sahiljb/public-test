window.addEventListener("load", function(){

    // Remove Loader
    var load_screen = document.getElementById("load_screen");
    
    document.body.removeChild(load_screen);

    var layoutName = 'Collapsible Menu';
    let bookThemeObject = '';
    var settingsObject = {
        admin: 'Book Admin Template',
        settings: {
            layout: {
                name: layoutName,
                darkMode: true,
            }
        },
        reset: false
    };

    if (settingsObject.reset) {
        sessionStorage.clear();
    }

    if (sessionStorage.length === 0) {
        bookThemeObject = settingsObject;
    } else {

        let getbookThemeObject = sessionStorage.getItem("theme");
        let getParseObject = JSON.parse(getbookThemeObject);
        let ParsedObject = getParseObject;

        if (getbookThemeObject !== null) {
               
            if (ParsedObject.admin === 'Book Admin Template') {

                if (ParsedObject.settings.layout.name === layoutName) {

                    bookThemeObject = ParsedObject;
                } else {
                    bookThemeObject = settingsObject;
                }
                
            } else {
                if (ParsedObject.admin === undefined) {
                    bookThemeObject = settingsObject;
                }
            }

        }  else {
            bookThemeObject = settingsObject;
        }
    }

    // Get Dark Mode Information i.e darkMode: true or false
    
    

    if (bookThemeObject.settings.layout.darkMode) {
        sessionStorage.setItem("theme", JSON.stringify(bookThemeObject));
        let getbookThemeObject = sessionStorage.getItem("theme");
        let getParseObject = JSON.parse(getbookThemeObject);
    
        if (getParseObject.settings.layout.darkMode) {
            let ifStarterKit = document.body.getAttribute('page') === 'starter-pack' ? true : false;
            document.body.classList.add('layout-dark');
            if (ifStarterKit) {
                if (document.querySelector('.navbar-logo')) {
                    document.querySelector('.navbar-logo').setAttribute('src', '/images/logo.svg');
                }
            }
        }
    } else {
        sessionStorage.setItem("theme", JSON.stringify(bookThemeObject));
        let getbookThemeObject = sessionStorage.getItem("theme");
        let getParseObject = JSON.parse(getbookThemeObject);

        if (!getParseObject.settings.layout.darkMode) {
            let ifStarterKit = document.body.getAttribute('page') === 'starter-pack' ? true : false;
            document.body.classList.remove('layout-dark');
            if (ifStarterKit) {
                if (document.querySelector('.navbar-logo')) {
                    document.querySelector('.navbar-logo').setAttribute('src', '../../src/assets/img/logo2.svg');
                }
            }
            
        }
    }

    // Get FULL WIDTH Layout

    if (document.body.getAttribute('layout') === 'full-width') {
        document.body.classList.remove('layout-boxed');
        if (document.querySelector('.header-container')) {
            document.querySelector('.header-container').classList.remove('container-xxl');
        }
        if (document.querySelector('.middle-content')) {
            document.querySelector('.middle-content').classList.remove('container-xxl');
        }
    }
    
});