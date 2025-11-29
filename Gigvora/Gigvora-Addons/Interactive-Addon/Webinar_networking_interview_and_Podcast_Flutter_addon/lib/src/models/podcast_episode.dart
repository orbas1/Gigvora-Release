import 'model_utils.dart';

class PodcastEpisode {
  final int id;
  final int podcastSeriesId;
  final String title;
  final String? description;
  final DateTime? scheduledFor;
  final DateTime? publishedAt;
  final String? audioPath;
  final int? duration;
  final bool isPublic;
  final Map<String, dynamic>? metadata;
  final DateTime? createdAt;
  final DateTime? updatedAt;

  PodcastEpisode({
    required this.id,
    required this.podcastSeriesId,
    required this.title,
    required this.isPublic,
    this.description,
    this.scheduledFor,
    this.publishedAt,
    this.audioPath,
    this.duration,
    this.metadata,
    this.createdAt,
    this.updatedAt,
  });

  factory PodcastEpisode.fromJson(Map<String, dynamic> json) {
    return PodcastEpisode(
      id: json['id'] as int,
      podcastSeriesId: json['podcast_series_id'] as int,
      title: json['title'] as String,
      description: json['description'] as String?,
      scheduledFor: parseDateTime(json['scheduled_for']),
      publishedAt: parseDateTime(json['published_at']),
      audioPath: json['audio_path'] as String?,
      duration: json['duration'] as int?,
      isPublic: json['is_public'] as bool? ?? false,
      metadata: parseMetadata(json['metadata']),
      createdAt: parseDateTime(json['created_at']),
      updatedAt: parseDateTime(json['updated_at']),
    );
  }
}

class PodcastEpisodePayload {
  final String title;
  final String? description;
  final DateTime? scheduledFor;
  final DateTime? publishedAt;
  final String? audioPath;
  final int? duration;
  final Map<String, dynamic>? metadata;
  final bool isPublic;

  PodcastEpisodePayload({
    required this.title,
    this.description,
    this.scheduledFor,
    this.publishedAt,
    this.audioPath,
    this.duration,
    this.metadata,
    this.isPublic = false,
  });

  Map<String, dynamic> toJson() => {
        'title': title,
        'description': description,
        'scheduled_for': scheduledFor?.toIso8601String(),
        'published_at': publishedAt?.toIso8601String(),
        'audio_path': audioPath,
        'duration': duration,
        if (metadata != null) 'metadata': metadata,
        'is_public': isPublic,
      };
}
