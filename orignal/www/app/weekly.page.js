// create page
var page = document.createElement("div");
page.className = "page";
page.name = "Weekly";
document.getElementById("page-view").appendChild(page);

// create menu entry
var menuEntry = document.createElement('img')
menuEntry.setAttribute("src", "/app/assets/img/goal.png")
menuEntry.setAttribute("alt", "Weekly Goal")
menuEntry.addEventListener('click', () => { changePage('Weekly'); })
document.getElementById("bottom-tabs").appendChild(menuEntry)

// title
var pageTitle = document.createElement('h2');
pageTitle.appendChild(document.createTextNode("Weekly Goal"))
page.appendChild(pageTitle)

// goal

page.appendChild(document.createTextNode("this is your "));
// console.log("Created page 'weekly'\n", page)