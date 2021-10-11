require('./bootstrap');

import Foundation from 'foundation-sites';

import Echo from 'laravel-echo';

window.Pusher = require('pusher-js');

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: process.env.MIX_PUSHER_APP_KEY,
    cluster: process.env.MIX_PUSHER_APP_CLUSTER,
    forceTLS: true
});

window.Echo.private(`user.${userId}.history`)
    .listen('history_update', (e) => {
        console.log(e);
    });

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
                        <td style="width:70px; !important">
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

let currentOffset = 50;

$(window).scroll(
    function () {
        if ($(window).scrollTop() === $(document).height() - $(window).height()) {
            $('#loading').css('display', 'block');

            updateOnScroll()
        }
    }
)

function updateOnScroll() {
    $.ajax(
        {
            url: window.app.name + '/spotify/tracks/' + currentOffset,
            success:
            function (e) {
                let rows = '';

                e.forEach(
                    function (element) {
                        rows += `
                            <tr>
                                <td>
                                    <img
                                        src="${element.image}"
                                        style="width:50px; height:50px;"
                                        alt="${element.albumName}"/>
                                </td>
                                <td>
                                    <b>${element.name}</b> - ${element.artists}
                                </td>
                                <td style="text-align:center;">
                                    ${element.duration}
                                </td>
                                <td style="text-align:center;">
                                    ${element.playbackCount}
                                </td>
                                <td style="text-align:center;">
                                    ${element.addedAt}
                                </td>
                                <td style="font-size:20pt; text-align:center;">
                                    <a data-tooltip class="top" title="Add to queue" onclick="addToQueue('${element.addToQueue}', '${element.name}');">
                                        <i class="fas fa-plus-circle"></i>
                                    </a>&nbsp;
                                    <a data-tooltip class="top" title="Play now" onclick="playNow('${element.addToQueue}')">
                                        <i class="fas fa-play-circle"></i>
                                    </a>
                                </td>
                            </tr>
                        `;
                    }
                );

                let html = $('#tracks').html()

                $('#tracks').html(html + rows);

                $('#loading').css('display', 'none');

                currentOffset += 50;
            }
        }
    )
}

$(document).ready(
    function() {

        updateRecommendations();
        updateCurrentlyPlaying();

        setInterval(updateCurrentlyPlaying, 20 * 1000);
    }
)

$(document).foundation();
$('.has-tip').foundation();
