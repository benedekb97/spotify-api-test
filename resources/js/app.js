require('./bootstrap');

import Foundation from 'foundation-sites';

window.playNow = function (url) {
    $.ajax({
        url: url,
        success: function ()
        {
            nextTrack();
        }
    })
}

window.addToQueue = function (url, name = null) {
    $.ajax({
        url: url,
        success: function () {
            name = name + ' ';

            let calloutsHtml = $('#callouts').html();

            calloutsHtml += `
                <div class="callout success" data-closable>
                    <p>Added <b>${name}</b>to queue!</p>
                    <button class="close-button" aria-label="Dismiss alert" type="button" data-close>
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            `;

            $('#callouts').html(calloutsHtml);
            $(document).foundation();
        }
    })
}

window.nextTrack = function () {
    moveTrack(window.app.name + '/spotify/next');
}

window.previousTrack = function () {
    moveTrack(window.app.name + '/spotify/previous');
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
        url: window.app.name + '/spotify/currently-playing',
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
        url: window.app.name + '/spotify/recommended',
        success: function (e) {
            let rows = '';

            e.tracks.forEach(
                function (element) {

                    let artists = element.artists.map(
                        function (artist) {
                            return artist.name;
                        }
                    ).join(', ');

                    let duration = new Date(element.durationMs);

                    rows += `
                    <tr>
                        <td>
                            <img src="${element.album.images[0].url}"
                                 style="width:50px; height:50px;"
                                 alt="${element.album.name}" />
                        </td>
                        <td>${artists}</td>
                        <td>${element.name}</td>
                        <td>${duration.getMinutes() + ':' + duration.getSeconds()}</td>
                        <td style="text-align:center;font-size:20pt;">
                            <a data-tooltip class="top" title="Add to queue" onclick="addToQueue(window.app.name + '/spotify/queue/add/${element.uri}', '${element.name}')">
                                <i class="fas fa-plus-circle"></i>
                            </a>&nbsp;
                            <a data-tooltip class="top" title="Play now" onclick="playNow(window.app.name + '/spotify/queue/add/${element.uri}')">
                                <i class="fas fa-play-circle"></i>
                            </a>
                        </td>
                    </tr>
                    `;
                }
            );

            $('#recommendations').html(rows);

            $(document).foundation();
            $('.has-tip').foundation();
        }
    });
}

$(document).ready(
    function() {
        // TODO: only do this for specific routes kek, now it refreshes every 20 seconds even if you aren't logged in. You get a 401 obv, but still, shite

        updateRecommendations();
        updateCurrentlyPlaying();

        setInterval(updateCurrentlyPlaying, 20 * 1000);
    }
)

$(document).foundation();
$('.has-tip').foundation();
