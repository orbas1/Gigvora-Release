import 'model_utils.dart';

class NetworkingParticipant {
  final int id;
  final int networkingSessionId;
  final int userId;
  final int? currentPartnerId;
  final int rotationPosition;
  final DateTime? joinedAt;
  final String status;

  NetworkingParticipant({
    required this.id,
    required this.networkingSessionId,
    required this.userId,
    required this.rotationPosition,
    required this.status,
    this.currentPartnerId,
    this.joinedAt,
  });

  factory NetworkingParticipant.fromJson(Map<String, dynamic> json) {
    return NetworkingParticipant(
      id: json['id'] as int,
      networkingSessionId: json['networking_session_id'] as int,
      userId: json['user_id'] as int,
      currentPartnerId: json['current_partner_id'] as int?,
      rotationPosition: json['rotation_position'] as int? ?? 1,
      joinedAt: parseDateTime(json['joined_at']),
      status: json['status'] as String? ?? 'registered',
    );
  }
}
