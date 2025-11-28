import 'model_utils.dart';

class Recording {
  final int id;
  final String path;
  final int? userId;
  final int? duration;
  final String? recordableType;
  final int? recordableId;
  final Map<String, dynamic>? metadata;
  final DateTime? createdAt;
  final DateTime? updatedAt;

  Recording({
    required this.id,
    required this.path,
    this.userId,
    this.duration,
    this.recordableType,
    this.recordableId,
    this.metadata,
    this.createdAt,
    this.updatedAt,
  });

  factory Recording.fromJson(Map<String, dynamic> json) {
    return Recording(
      id: json['id'] as int,
      path: json['path'] as String,
      userId: json['user_id'] as int?,
      duration: json['duration'] as int?,
      recordableType: json['recordable_type'] as String?,
      recordableId: json['recordable_id'] as int?,
      metadata: parseMetadata(json['metadata']),
      createdAt: parseDateTime(json['created_at']),
      updatedAt: parseDateTime(json['updated_at']),
    );
  }
}
