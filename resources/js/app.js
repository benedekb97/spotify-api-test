require('./bootstrap');

window.playNow = function (url) {
    $.ajax({
        url: url,
        success: function ()
        {
            nextTrack();
        }
    })
}

window.addToQueue = function (url) {
    $.ajax({
        url: url
    })
}

window.nextTrack = function () {
    moveTrack('http://spotify.local/spotify/next');
}

window.previousTrack = function () {
    moveTrack('http://spotify.local/spotify/previous');
}

window.moveTrack = function (url) {
    $.ajax({
        url: url,
        success: function() {
            setTimeout(
                function ()
                {
                    updateCurrentlyPlaying();
                },
                500
            )
        }
    })
}

window.updateCurrentlyPlaying = function () {
    $.ajax({
        url: 'http://spotify.local/spotify/currently-playing',
        success: function(e) {
            if (e.item === undefined) {
                $('#currentlyPlayingImg').attr('src', '').attr('alt', '');
                $('#currentlyPlayingText').html(
                    `<i>Nothing here...</i>`
                );
            } else {
                $('#currentlyPlayingImg')
                    .attr('src', e.item.album.images[0].url)
                    .attr('alt', e.item.album.name);

                let artists = e.item.artists.map(
                    function (element) {
                        return element.name;
                    }
                ).join(', ')

                $('#currentlyPlayingText').html(
                    `<b>${artists}</b> - ${e.item.name}`
                );
            }
        }
    });
}

window.updateRecommendations = function () {
    $.ajax({
        url: 'http://spotify.local/spotify/recommended',
        success: function (e) {
            let rows = '';

            e.tracks.forEach(
                function (element) {

                    let artists = element.artists.map(
                        function (artist) {
                            return artist.name;
                        }
                    ).join(', ');

                    rows += `
                    <tr>
                        <td>
                            <img src="${element.album.images[0].url}"
                                 style="width:50px; height:50px;"
                                 alt="${element.album.name}" />
                        </td>
                        <td>${artists}</td>
                        <td>${element.name}</td>
                        <td style="text-align:center;">
                            <a href="#" onclick="addToQueue('http://spotify.local/spotify/queue/add/${element.uri}')">Add to queue</a><br>
                            <a href="#" onclick="playNow('http://spotify.local/spotify/queue/add/${element.uri}')">Play now</a>
                        </td>
                    </tr>
                    `;
                }
            );

            $('#recommendations').html(rows);
        }
    });
}

$(document).ready(
    function() {
        updateRecommendations();
        updateCurrentlyPlaying();
    }
)
