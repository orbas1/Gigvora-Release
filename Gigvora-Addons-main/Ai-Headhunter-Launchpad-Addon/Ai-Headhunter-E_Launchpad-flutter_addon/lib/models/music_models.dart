class MusicTrack {
  final int id;
  final String title;
  final String artist;
  final Duration? duration;
  final String? url;

  const MusicTrack({
    required this.id,
    required this.title,
    required this.artist,
    this.duration,
    this.url,
  });

  factory MusicTrack.fromJson(Map<String, dynamic> json) {
    return MusicTrack(
      id: json['id'] as int,
      title: json['title'] as String? ?? '',
      artist: json['artist'] as String? ?? '',
      duration: json['duration'] != null
          ? Duration(seconds: (json['duration'] as num).toInt())
          : null,
      url: json['url'] as String?,
    );
  }

  Map<String, dynamic> toJson() => {
        'id': id,
        'title': title,
        'artist': artist,
        if (duration != null) 'duration': duration!.inSeconds,
        if (url != null) 'url': url,
      };
}
