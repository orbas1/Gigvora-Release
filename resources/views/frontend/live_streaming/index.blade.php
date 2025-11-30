<?php
    $zoom_configuration = get_settings('zoom_configuration', true);
?>
@php
    $liveConfig = $post_details->live_config ? json_decode($post_details->live_config, true) : [];
    $ctaLinks = $liveConfig['cta_links'] ?? [];
    $stickers = config('media_studio.stickers', []);
@endphp


<!DOCTYPE html>

<head>
    <title><?php echo get_phrase('Live class'); ?> : <?php echo get_phrase('Page title'); ?></title>
    <meta charset="utf-8" />
    <link type="text/css" rel="stylesheet" href="https://source.zoom.us/2.6.0/css/bootstrap.css" />
    <link type="text/css" rel="stylesheet" href="https://source.zoom.us/2.6.0/css/react-select.css" />
    <script src="https://source.zoom.us/1.7.2/lib/vendor/jquery.min.js"></script>
    <meta name="format-detection" content="telephone=no">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
</head>

<body>
    <div class="gv-live-shell">
        <div class="gv-live-shell__player">
            <div id="zmmtg-root"></div>
            <div id="aria-notify-area"></div>
        </div>
        <aside class="gv-live-shell__panel">
            <div class="live-panel-section">
                <h3>{{ get_phrase('Donation progress') }}</h3>
                <div id="liveDonationProgressOuter">
                    <span id="liveDonationProgressInner"></span>
                </div>
                <small id="liveDonationLabel" class="text-muted d-block mb-2">£0 / £0</small>
                <div class="live-stat-grid">
                    <div>
                        <p class="mb-1">{{ get_phrase('Viewers now') }}</p>
                        <strong id="liveViewerNow">0</strong>
                    </div>
                    <div>
                        <p class="mb-1">{{ get_phrase('Goal') }}</p>
                        <strong id="liveViewerGoal">0</strong>
                    </div>
                    <div>
                        <p class="mb-1">{{ get_phrase('Peak') }}</p>
                        <strong id="liveViewerPeak">0</strong>
                    </div>
                </div>
            </div>

            <div class="live-panel-section">
                <h3>{{ get_phrase('Top supporters') }}</h3>
                <ul id="liveSupporters" class="list-unstyled mb-0"></ul>
            </div>

            <div class="live-panel-section">
                <h3>{{ get_phrase('Recent activity') }}</h3>
                <div id="liveRecentActivity" class="small text-muted"></div>
            </div>

            <div class="live-panel-section">
                <h3>{{ get_phrase('Support the host') }}</h3>
                <form id="liveDonationForm">
                    <input type="number" min="1" step="1" name="amount" placeholder="{{ get_phrase('Amount (£)') }}" required>
                    <textarea name="message" rows="2" placeholder="{{ get_phrase('Message (optional)') }}"></textarea>
                    <select name="sticker">
                        <option value="">{{ get_phrase('No sticker') }}</option>
                        @foreach ($stickers as $key => $sticker)
                            <option value="{{ $key }}">{{ $sticker['label'] }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-primary w-100">{{ get_phrase('Send donation') }}</button>
                </form>
            </div>

            <div class="live-panel-section">
                <h3>{{ get_phrase('React') }}</h3>
                <div class="live-reactions">
                    <button type="button" data-live-reaction="like">👍</button>
                    <button type="button" data-live-reaction="love">❤️</button>
                    <button type="button" data-live-reaction="fire">🔥</button>
                    <button type="button" data-live-reaction="wow">🤯</button>
                </div>
            </div>

            <div class="live-panel-section">
                <h3>{{ get_phrase('Ask a question') }}</h3>
                <form id="liveQuestionForm">
                    <textarea name="question" rows="2" placeholder="{{ get_phrase('Type your question...') }}" required></textarea>
                    <button type="submit" class="btn btn-secondary w-100">{{ get_phrase('Submit') }}</button>
                </form>
            </div>

            @if (!empty($ctaLinks))
                <div class="live-panel-section">
                    <h3>{{ get_phrase('Featured links') }}</h3>
                    <ul class="live-cta-links">
                        @foreach ($ctaLinks as $link)
                            <li><a href="{{ $link['url'] }}" target="_blank" rel="noopener">{{ $link['label'] }}</a></li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="live-panel-section">
                <h3>{{ get_phrase('Polls & utilities') }}</h3>
                <a href="{{ Route::has('utilities.posts.poll') ? route('utilities.posts.poll') : route('utilities.hub') }}" class="btn btn-outline-primary w-100">
                    {{ get_phrase('Launch Utilities Polls') }}
                </a>
            </div>
        </aside>
    </div>
    <style>
        body {
            padding-top: 50px;
        }

        .course_info {
            color: #999999;
            font-size: 11px;
            padding-bottom: 10px;
        }

        .btn-finish {
            background-color: #656565;
            border-color: #222222;
            color: #cacaca;
        }

        .btn-finish:hover,
        .btn-finish:focus,
        .btn-finish:active,
        .btn-finish.active,
        .open .dropdown-toggle.btn-finish {
            color: #cacaca;
        }

        .course_user_info {
            color: #989898;
            font-size: 12px;
            margin-right: 20px;
        }

        .gv-live-shell {
            display: grid;
            grid-template-columns: minmax(0, 1fr) 320px;
            gap: 24px;
            padding: 24px;
        }

        .gv-live-shell__panel {
            background: #fff;
            border: 1px solid rgba(148, 163, 184, 0.4);
            border-radius: 18px;
            padding: 18px;
            max-height: calc(100vh - 80px);
            overflow: auto;
        }

        .gv-live-shell__panel h3 {
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .live-panel-section {
            margin-bottom: 24px;
        }

        .live-panel-section form input,
        .live-panel-section form textarea,
        .live-panel-section form select {
            width: 100%;
            border: 1px solid rgba(148, 163, 184, 0.4);
            border-radius: 8px;
            padding: 8px 10px;
            margin-bottom: 8px;
        }

        .live-reactions button {
            border: none;
            background: rgba(37, 99, 235, 0.08);
            border-radius: 999px;
            padding: 8px 14px;
            font-size: 1rem;
            margin-right: 6px;
        }

        .live-cta-links {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .live-cta-links li {
            margin-bottom: 6px;
        }

        .live-cta-links a {
            text-decoration: none;
            font-weight: 600;
        }

        #liveDonationProgressOuter {
            width: 100%;
            height: 8px;
            border-radius: 999px;
            background: rgba(37, 99, 235, 0.15);
            overflow: hidden;
            margin-bottom: 6px;
        }

        #liveDonationProgressInner {
            display: block;
            height: 100%;
            background: linear-gradient(90deg, #2563eb, #60a5fa);
            width: 0%;
        }

        .live-stat-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 12px;
            margin-top: 12px;
        }

        .live-stat-grid div {
            border: 1px solid rgba(148, 163, 184, 0.35);
            border-radius: 12px;
            padding: 8px;
            text-align: center;
        }

        #liveSupporters li {
            display: flex;
            justify-content: space-between;
            border-bottom: 1px dashed rgba(148, 163, 184, 0.3);
            padding: 4px 0;
        }
    </style>

    <script src="https://source.zoom.us/2.6.0/lib/vendor/react.min.js"></script>
    <script src="https://source.zoom.us/2.6.0/lib/vendor/react-dom.min.js"></script>
    <script src="https://source.zoom.us/2.6.0/lib/vendor/redux.min.js"></script>
    <script src="https://source.zoom.us/2.6.0/lib/vendor/redux-thunk.min.js"></script>
    <script src="https://source.zoom.us/2.6.0/lib/vendor/lodash.min.js"></script>
    <script src="https://source.zoom.us/zoom-meeting-2.6.0.min.js"></script>

    <script>
        "use strict";

        function stop_zoom() {
            var r = confirm("<?php echo get_phrase('Do you want to leave the live video'); ?> ? <?php echo get_phrase('You can join them later if the video remains live'); ?>");
            if (r == true) {
                ZoomMtg.leaveMeeting();
            }

        }

        $(document).ready(function() {
            start_zoom();
        });

        function start_zoom() {

            ZoomMtg.preLoadWasm();
            ZoomMtg.prepareJssdk();

            var API_KEY = "{{$zoom_configuration['api_key']}}";
            var API_SECRET = "{{$zoom_configuration['api_secret']}}";
            var USER_NAME = "{{Auth()->user()->name}}";
            var MEETING_NUMBER = "{{$meeting_details['id']}}";
            var PASSWORD = "{{$meeting_details['password']}}";


            var leave_url = "@if($host == 1){{route('zoom-meeting-leave-url', $post_details->post_id)}}@else{{url('/')}}@endif";

            var testTool = window.testTool;


            var meetConfig = {
                apiKey: API_KEY,
                apiSecret: API_SECRET,
                meetingNumber: MEETING_NUMBER,
                userName: USER_NAME,
                passWord: PASSWORD,
                leaveUrl: leave_url,
                role: "{{$host}}"
            };



            var signature = ZoomMtg.generateSignature({
                meetingNumber: meetConfig.meetingNumber,
                apiKey: meetConfig.apiKey,
                apiSecret: meetConfig.apiSecret,
                role: meetConfig.role,
                success: function(res) {
                    console.log(res.result);
                }
            });

            ZoomMtg.init({
                leaveUrl: meetConfig.leaveUrl,
                showMeetingHeader: true,
                isSupportAV: {{$isSupportAV}},
                isSupportChat: true,
                disableJoinAudio: {{$disableJoinAudio}},
                success: function() {
                    ZoomMtg.join({
                        meetingNumber: meetConfig.meetingNumber,
                        userName: meetConfig.userName,
                        signature: signature,
                        apiKey: meetConfig.apiKey,
                        passWord: meetConfig.passWord,
                        success: function(res) {
                            console.log('Successfully joined');
                        },
                        error: function(res) {
                            console.log(res);
                        }
                    });
                },
                error: function(res) {
                    console.log(res);
                }
            });
        }
    </script>

    <script>
        (function () {
            const endpoints = {
                summary: "{{ route('live.engagement.summary', $post_details->post_id) }}",
                donate: "{{ route('live.engagement.donate', $post_details->post_id) }}",
                react: "{{ route('live.engagement.react', $post_details->post_id) }}",
                question: "{{ route('live.engagement.question', $post_details->post_id) }}",
            };

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                }
            });

            const currency = new Intl.NumberFormat('en-GB', { style: 'currency', currency: 'GBP' });

            function renderSummary(data) {
                $('#liveDonationProgressInner').css('width', `${data.progress || 0}%`);
                $('#liveDonationLabel').text(`${currency.format(data.total || 0)} / ${currency.format(data.goal || 0)}`);
                $('#liveViewerNow').text(data.viewer_count || 0);
                $('#liveViewerGoal').text(data.viewer_goal || 0);
                $('#liveViewerPeak').text(data.viewer_peak || 0);

                const supportersEl = $('#liveSupporters').empty();
                (data.leaderboard || []).forEach((leader) => {
                    supportersEl.append(`<li><span>${leader.name}</span><span>${currency.format(leader.amount)}</span></li>`);
                });

                const recentEl = $('#liveRecentActivity').empty();
                (data.recent_donations || []).forEach((entry) => {
                    recentEl.append(`<div><strong>${entry.user.name}</strong> · ${currency.format(entry.amount)}</div>`);
                });
            }

            function refreshSummary() {
                $.getJSON(endpoints.summary, renderSummary);
            }

            $('#liveDonationForm').on('submit', function (event) {
                event.preventDefault();
                const $form = $(this);
                $.post(endpoints.donate, $form.serialize())
                    .done((response) => {
                        $form.trigger('reset');
                        if (response.summary) {
                            renderSummary(response.summary);
                        } else {
                            refreshSummary();
                        }
                    });
            });

            $('[data-live-reaction]').on('click', function () {
                const reaction = $(this).data('liveReaction');
                $.post(endpoints.react, { reaction });
            });

            $('#liveQuestionForm').on('submit', function (event) {
                event.preventDefault();
                const $form = $(this);
                $.post(endpoints.question, $form.serialize())
                    .done(() => {
                        $form.trigger('reset');
                    });
            });

            refreshSummary();
            setInterval(refreshSummary, 15000);
        })();
    </script>
</body>

</html>