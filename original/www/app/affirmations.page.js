// create page
var page = document.createElement("div");
page.className = "page";
page.name = "Affirmations";

var pageTitle = document.createElement('h2');
pageTitle.appendChild(document.createTextNode("Affirmations"))
page.appendChild(pageTitle)

// create menu entry
var menuEntry = document.createElement('img')
menuEntry.setAttribute("src", "/app/assets/img/affirmations.png")
menuEntry.setAttribute("alt", "Affirmations")
menuEntry.addEventListener('click', () => { changePage('Affirmations'); })
document.getElementById("bottom-tabs").appendChild(menuEntry)

function pageSetup(page) {

    var list = [
        { title: "first element", description: "this is my first element" },
        { title: "second element", description: "this is my second element" }
    ];

    list.forEach((elem) => {
        var e = document.createElement("div");
        var title = document.getElementById("h4");
        title.setAttribute("class", "list-title");
        title.appendChild(docuemnt.createTextNode(elem.title));
        e.appendChild(title);
        var descritpion = document.getElementById("p");
        descritpion.setAttribute("list-description");
        descritpion.appendChild(elem.description);
        e.appendChild(descritpion);
        page.appendChild(e);
    });
}

pageSetup(page);
document.getElementById("page-view").appendChild(page);
// console.log("Created page 'affirmation'\n", page)