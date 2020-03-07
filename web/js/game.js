$( document ).ready(function() {
    setTimeout(function(){
        let nextMoveLocation = $('#nextMove').val();
        console.log(`nextMoveLocation = ${nextMoveLocation}`);
        if( nextMoveLocation ) {
            // location="http://localhost/JobExams/caveret/tic-test/web/play/1-1";
            location = nextMoveLocation;
        }
    },2000)
});