
// create page
var page = document.createElement("div");
page.className = "Page";
page.name = "Affirmations";

function pageSetup(page) {

    var list = [
        {title: "first element", description: "this is my first element"},
        {title: "second element", description: "this is my second element"}
    ];

    list.forEach( (elem) => {
        var e = document.createElement("div");
        var title = document.getElementById("h4");
        title.setAttribute("class", "list-title");
        title.appendChild(elem.title);
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