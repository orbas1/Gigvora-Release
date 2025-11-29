import 'model_utils.dart';
import 'networking_participant.dart';

class NetworkingSession {
  final int id;
  final int hostId;
  final String title;
  final String? description;
  final int durationSeconds;
  final int rotationInterval;
  final DateTime startsAt;
  final bool isPaid;
  final double? price;
  final String status;
  final Map<String, dynamic>? metadata;
  final DateTime? createdAt;
  final DateTime? updatedAt;
  final List<NetworkingParticipant> participants;

  NetworkingSession({
    required this.id,
    required this.hostId,
    required this.title,
    required this.durationSeconds,
    required this.rotationInterval,
    required this.startsAt,
    required this.isPaid,
    required this.status,
    this.description,
    this.price,
    this.metadata,
    this.createdAt,
    this.updatedAt,
    this.participants = const [],
  });

  factory NetworkingSession.fromJson(Map<String, dynamic> json) {
    return NetworkingSession(
      id: json['id'] as int,
      hostId: json['host_id'] as int,
      title: json['title'] as String,
      description: json['description'] as String?,
      durationSeconds: json['duration_seconds'] as int,
      rotationInterval: json['rotation_interval'] as int,
      startsAt: parseDateTime(json['starts_at']) ?? DateTime.now(),
      isPaid: json['is_paid'] as bool? ?? false,
      price: (json['price'] as num?)?.toDouble(),
      status: json['status'] as String? ?? 'scheduled',
      metadata: parseMetadata(json['metadata']),
      createdAt: parseDateTime(json['created_at']),
      updatedAt: parseDateTime(json['updated_at']),
      participants: (json['participants'] as List<dynamic>? ?? [])
          .map((item) =>
              NetworkingParticipant.fromJson(Map<String, dynamic>.from(item as Map)))
          .toList(),
    );
  }
}

class NetworkingSessionPayload {
  final String title;
  final String? description;
  final int durationSeconds;
  final int rotationInterval;
  final DateTime startsAt;
  final bool isPaid;
  final double? price;
  final Map<String, dynamic>? metadata;

  NetworkingSessionPayload({
    required this.title,
    required this.durationSeconds,
    required this.rotationInterval,
    required this.startsAt,
    this.description,
    this.isPaid = false,
    this.price,
    this.metadata,
  });

  Map<String, dynamic> toJson() => {
        'title': title,
        'description': description,
        'duration_seconds': durationSeconds,
        'rotation_interval': rotationInterval,
        'starts_at': startsAt.toIso8601String(),
        'is_paid': isPaid,
        'price': price,
        if (metadata != null) 'metadata': metadata,
      };
}

class NetworkingSessionUpdatePayload {
  final String? title;
  final String? description;
  final int? durationSeconds;
  final int? rotationInterval;
  final DateTime? startsAt;
  final bool? isPaid;
  final double? price;
  final String? status;
  final Map<String, dynamic>? metadata;

  NetworkingSessionUpdatePayload({
    this.title,
    this.description,
    this.durationSeconds,
    this.rotationInterval,
    this.startsAt,
    this.isPaid,
    this.price,
    this.status,
    this.metadata,
  });

  Map<String, dynamic> toJson() => {
        if (title != null) 'title': title,
        if (description != null) 'description': description,
        if (durationSeconds != null) 'duration_seconds': durationSeconds,
        if (rotationInterval != null) 'rotation_interval': rotationInterval,
        if (startsAt != null) 'starts_at': startsAt!.toIso8601String(),
        if (isPaid != null) 'is_paid': isPaid,
        if (price != null) 'price': price,
        if (status != null) 'status': status,
        if (metadata != null) 'metadata': metadata,
      };
}
