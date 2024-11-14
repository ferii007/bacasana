
const getDataArticle = () => {
    let getData = $.ajax({
        type: "GET",
        url: `https://dummyapi.online/api/blogposts`,
        dataType: "json",
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    getData.done(function( msg ) {
        console.log('mesg', msg);
        renderDataArticle(msg);
    });
       
    getData.fail(function( jqXHR, textStatus ) {
        alert( "Request failed: " + textStatus );
    });
}

const renderDataArticle = async (dataArticle) => {
    const articleForHeroSection = dataArticle[0];
    const remainingArticles = dataArticle.slice(1);

    console.log('articleForHeroSection', articleForHeroSection)
    console.log('remainingArticles', remainingArticles)
}

const renderArticleForHeroSection = async () => {
    
}


$(document).ready(function() {
    console.log( "ready!" );

    getDataArticle();
});