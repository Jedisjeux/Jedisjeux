$(function() {
    // Document is ready

    var defaultOptions = {
        'starCaptions': {
            0.5: 'Nul',
            1: 'Très mauvais',
            1.5: 'Mauvais',
            2: 'Bof',
            2.5: 'Moyen',
            3: 'Pas mal',
            3.5: 'Bon',
            4: 'Très bon',
            4.5: 'Excellent',
            5: 'Mythique'
        }
    };

    $(".game-rating").rating(defaultOptions);
});
