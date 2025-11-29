import 'package:equatable/equatable.dart';

class ProfilePortfolio extends Equatable {
  const ProfilePortfolio({
    required this.id,
    required this.userId,
    required this.title,
    this.description,
    this.link,
    this.thumbnailUrl,
    this.featured = false,
    this.completedAt,
  });

  final int id;
  final int userId;
  final String title;
  final String? description;
  final String? link;
  final String? thumbnailUrl;
  final bool featured;
  final DateTime? completedAt;

  factory ProfilePortfolio.fromJson(Map<String, dynamic>? json) {
    if (json == null) {
      return const ProfilePortfolio(id: 0, userId: 0, title: '');
    }

    DateTime? _parseDate(dynamic value) {
      if (value == null) return null;
      return DateTime.tryParse(value.toString());
    }

    return ProfilePortfolio(
      id: json['id'] is int ? json['id'] : int.tryParse(json['id']?.toString() ?? '') ?? 0,
      userId: json['user_id'] is int ? json['user_id'] : int.tryParse(json['user_id']?.toString() ?? '') ?? 0,
      title: json['title']?.toString() ?? '',
      description: json['description']?.toString(),
      link: json['link']?.toString(),
      thumbnailUrl: json['thumbnail_url']?.toString(),
      featured: json['featured'] == true || json['featured']?.toString() == '1',
      completedAt: _parseDate(json['completed_at']),
    );
  }

  Map<String, dynamic> toJson() => {
        'id': id,
        'user_id': userId,
        'title': title,
        'description': description,
        'link': link,
        'thumbnail_url': thumbnailUrl,
        'featured': featured,
        'completed_at': completedAt?.toIso8601String(),
      };

  @override
  List<Object?> get props => [id, userId, title, description, link, thumbnailUrl, featured, completedAt];
}
