function changePage(pageName) {
    var view = document.getElementById("page-view");
    var foundPage = false;
    Array.from(view.children).forEach(page => {
        if (page.name.toLowerCase() == pageName.toLowerCase()) {
            if (!page.classList.contains("currentPage"))
                page.classList.add("currentPage");
            console.log("Moving to page " + page.name)
            foundPage = true;
        } else {
            if (page.classList.contains("currentPage"))
                page.classList.remove("currentPage");
        }
    });
    // if (!foundPage && pageName != "weekly")
    //     return changePage("weekly");

    return foundPage;
}