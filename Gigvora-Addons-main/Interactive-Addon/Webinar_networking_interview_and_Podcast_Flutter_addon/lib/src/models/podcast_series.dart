import 'model_utils.dart';
import 'podcast_episode.dart';

class PodcastSeries {
  final int id;
  final int hostId;
  final String title;
  final String? description;
  final String? coverArtPath;
  final bool isPublic;
  final Map<String, dynamic>? metadata;
  final DateTime? createdAt;
  final DateTime? updatedAt;
  final List<PodcastEpisode> episodes;

  PodcastSeries({
    required this.id,
    required this.hostId,
    required this.title,
    required this.isPublic,
    this.description,
    this.coverArtPath,
    this.metadata,
    this.createdAt,
    this.updatedAt,
    this.episodes = const [],
  });

  factory PodcastSeries.fromJson(Map<String, dynamic> json) {
    return PodcastSeries(
      id: json['id'] as int,
      hostId: json['host_id'] as int,
      title: json['title'] as String,
      description: json['description'] as String?,
      coverArtPath: json['cover_art_path'] as String?,
      isPublic: json['is_public'] as bool? ?? false,
      metadata: parseMetadata(json['metadata']),
      createdAt: parseDateTime(json['created_at']),
      updatedAt: parseDateTime(json['updated_at']),
      episodes: (json['episodes'] as List<dynamic>? ?? [])
          .map((item) => PodcastEpisode.fromJson(Map<String, dynamic>.from(item as Map)))
          .toList(),
    );
  }
}

class PodcastSeriesPayload {
  final String title;
  final String? description;
  final String? coverArtPath;
  final bool isPublic;
  final Map<String, dynamic>? metadata;

  PodcastSeriesPayload({
    required this.title,
    this.description,
    this.coverArtPath,
    this.isPublic = false,
    this.metadata,
  });

  Map<String, dynamic> toJson() => {
        'title': title,
        'description': description,
        'cover_art_path': coverArtPath,
        'is_public': isPublic,
        if (metadata != null) 'metadata': metadata,
      };
}

class PodcastSeriesUpdatePayload {
  final String? title;
  final String? description;
  final String? coverArtPath;
  final bool? isPublic;
  final Map<String, dynamic>? metadata;

  PodcastSeriesUpdatePayload({
    this.title,
    this.description,
    this.coverArtPath,
    this.isPublic,
    this.metadata,
  });

  Map<String, dynamic> toJson() => {
        if (title != null) 'title': title,
        if (description != null) 'description': description,
        if (coverArtPath != null) 'cover_art_path': coverArtPath,
        if (isPublic != null) 'is_public': isPublic,
        if (metadata != null) 'metadata': metadata,
      };
}
