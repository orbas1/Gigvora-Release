import 'model_utils.dart';
import 'webinar_registration.dart';
import 'recording.dart';

class Webinar {
  final int id;
  final int hostId;
  final String title;
  final String? description;
  final DateTime startsAt;
  final DateTime endsAt;
  final bool isLive;
  final bool isPaid;
  final double? price;
  final String? waitingRoomMessage;
  final String? streamProvider;
  final String? rtmpEndpoint;
  final String? recordingPath;
  final String status;
  final Map<String, dynamic>? metadata;
  final DateTime? createdAt;
  final DateTime? updatedAt;
  final List<WebinarRegistration> registrations;
  final List<Recording> recordings;
  final Map<String, dynamic>? host;

  Webinar({
    required this.id,
    required this.hostId,
    required this.title,
    required this.startsAt,
    required this.endsAt,
    required this.isLive,
    required this.isPaid,
    required this.status,
    this.description,
    this.price,
    this.waitingRoomMessage,
    this.streamProvider,
    this.rtmpEndpoint,
    this.recordingPath,
    this.metadata,
    this.createdAt,
    this.updatedAt,
    this.registrations = const [],
    this.recordings = const [],
    this.host,
  });

  factory Webinar.fromJson(Map<String, dynamic> json) {
    return Webinar(
      id: json['id'] as int,
      hostId: json['host_id'] as int,
      title: json['title'] as String,
      description: json['description'] as String?,
      startsAt: parseDateTime(json['starts_at']) ?? DateTime.now(),
      endsAt: parseDateTime(json['ends_at']) ?? DateTime.now(),
      isLive: json['is_live'] as bool? ?? false,
      isPaid: json['is_paid'] as bool? ?? false,
      price: (json['price'] as num?)?.toDouble(),
      waitingRoomMessage: json['waiting_room_message'] as String?,
      streamProvider: json['stream_provider'] as String?,
      rtmpEndpoint: json['rtmp_endpoint'] as String?,
      recordingPath: json['recording_path'] as String?,
      status: json['status'] as String? ?? 'scheduled',
      metadata: parseMetadata(json['metadata']),
      createdAt: parseDateTime(json['created_at']),
      updatedAt: parseDateTime(json['updated_at']),
      registrations: (json['registrations'] as List<dynamic>? ?? [])
          .map((item) => WebinarRegistration.fromJson(
              Map<String, dynamic>.from(item as Map)))
          .toList(),
      recordings: (json['recordings'] as List<dynamic>? ?? [])
          .map((item) => Recording.fromJson(Map<String, dynamic>.from(item as Map)))
          .toList(),
      host: json['host'] == null ? null : Map<String, dynamic>.from(json['host'] as Map),
    );
  }
}

class WebinarPayload {
  final String title;
  final DateTime startsAt;
  final DateTime endsAt;
  final String? description;
  final bool isPaid;
  final double? price;
  final String? waitingRoomMessage;
  final String? streamProvider;
  final String? rtmpEndpoint;
  final Map<String, dynamic>? metadata;

  WebinarPayload({
    required this.title,
    required this.startsAt,
    required this.endsAt,
    this.description,
    this.isPaid = false,
    this.price,
    this.waitingRoomMessage,
    this.streamProvider,
    this.rtmpEndpoint,
    this.metadata,
  });

  Map<String, dynamic> toJson() => {
        'title': title,
        'description': description,
        'starts_at': startsAt.toIso8601String(),
        'ends_at': endsAt.toIso8601String(),
        'is_paid': isPaid,
        'price': price,
        'waiting_room_message': waitingRoomMessage,
        'stream_provider': streamProvider,
        'rtmp_endpoint': rtmpEndpoint,
        if (metadata != null) 'metadata': metadata,
      };
}

class WebinarUpdatePayload {
  final String? title;
  final DateTime? startsAt;
  final DateTime? endsAt;
  final String? description;
  final bool? isPaid;
  final double? price;
  final String? waitingRoomMessage;
  final String? streamProvider;
  final String? rtmpEndpoint;
  final String? status;
  final Map<String, dynamic>? metadata;

  WebinarUpdatePayload({
    this.title,
    this.startsAt,
    this.endsAt,
    this.description,
    this.isPaid,
    this.price,
    this.waitingRoomMessage,
    this.streamProvider,
    this.rtmpEndpoint,
    this.status,
    this.metadata,
  });

  Map<String, dynamic> toJson() => {
        if (title != null) 'title': title,
        if (description != null) 'description': description,
        if (startsAt != null) 'starts_at': startsAt!.toIso8601String(),
        if (endsAt != null) 'ends_at': endsAt!.toIso8601String(),
        if (isPaid != null) 'is_paid': isPaid,
        if (price != null) 'price': price,
        if (waitingRoomMessage != null) 'waiting_room_message': waitingRoomMessage,
        if (streamProvider != null) 'stream_provider': streamProvider,
        if (rtmpEndpoint != null) 'rtmp_endpoint': rtmpEndpoint,
        if (status != null) 'status': status,
        if (metadata != null) 'metadata': metadata,
      };
}
