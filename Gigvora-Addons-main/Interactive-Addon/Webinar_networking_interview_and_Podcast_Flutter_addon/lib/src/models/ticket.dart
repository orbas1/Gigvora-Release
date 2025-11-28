import 'model_utils.dart';

class Ticket {
  final int id;
  final int? userId;
  final double price;
  final String currency;
  final String status;
  final Map<String, dynamic>? metadata;
  final String? ticketableType;
  final int? ticketableId;
  final DateTime? createdAt;
  final DateTime? updatedAt;

  Ticket({
    required this.id,
    required this.price,
    required this.currency,
    required this.status,
    this.userId,
    this.metadata,
    this.ticketableType,
    this.ticketableId,
    this.createdAt,
    this.updatedAt,
  });

  factory Ticket.fromJson(Map<String, dynamic> json) {
    return Ticket(
      id: json['id'] as int,
      userId: json['user_id'] as int?,
      price: (json['price'] as num?)?.toDouble() ?? 0,
      currency: json['currency'] as String? ?? 'USD',
      status: json['status'] as String? ?? 'available',
      metadata: parseMetadata(json['metadata']),
      ticketableType: json['ticketable_type'] as String?,
      ticketableId: json['ticketable_id'] as int?,
      createdAt: parseDateTime(json['created_at']),
      updatedAt: parseDateTime(json['updated_at']),
    );
  }
}
