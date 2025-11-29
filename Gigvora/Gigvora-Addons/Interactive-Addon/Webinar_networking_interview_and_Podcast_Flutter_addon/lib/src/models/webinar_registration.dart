import 'model_utils.dart';

class WebinarRegistration {
  final int id;
  final int webinarId;
  final int userId;
  final int? ticketId;
  final String status;
  final DateTime? checkedInAt;
  final DateTime? createdAt;
  final DateTime? updatedAt;

  WebinarRegistration({
    required this.id,
    required this.webinarId,
    required this.userId,
    required this.status,
    this.ticketId,
    this.checkedInAt,
    this.createdAt,
    this.updatedAt,
  });

  factory WebinarRegistration.fromJson(Map<String, dynamic> json) {
    return WebinarRegistration(
      id: json['id'] as int,
      webinarId: json['webinar_id'] as int,
      userId: json['user_id'] as int,
      ticketId: json['ticket_id'] as int?,
      status: json['status'] as String? ?? 'registered',
      checkedInAt: parseDateTime(json['checked_in_at']),
      createdAt: parseDateTime(json['created_at']),
      updatedAt: parseDateTime(json['updated_at']),
    );
  }
}
