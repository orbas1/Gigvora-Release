class Escrow {
  final int id;
  final int orderId;
  final double amount;
  final String status;
  final List<EscrowMilestone> milestones;

  const Escrow({
    required this.id,
    required this.orderId,
    required this.amount,
    required this.status,
    this.milestones = const [],
  });

  factory Escrow.fromJson(Map<String, dynamic> json) {
    return Escrow(
      id: json['id'] as int,
      orderId: json['order_id'] as int? ?? json['orderId'] as int,
      amount: (json['amount'] as num).toDouble(),
      status: json['status'] as String? ?? 'pending',
      milestones: (json['milestones'] as List? ?? [])
          .map((e) => EscrowMilestone.fromJson(e as Map<String, dynamic>))
          .toList(),
    );
  }

  Map<String, dynamic> toJson() => {
        'id': id,
        'order_id': orderId,
        'amount': amount,
        'status': status,
        'milestones': milestones.map((e) => e.toJson()).toList(),
      };
}

class EscrowMilestone {
  final String title;
  final double amount;
  final String status;
  final DateTime? dueDate;

  const EscrowMilestone({
    required this.title,
    required this.amount,
    this.status = 'pending',
    this.dueDate,
  });

  factory EscrowMilestone.fromJson(Map<String, dynamic> json) {
    return EscrowMilestone(
      title: json['title'] as String? ?? '',
      amount: (json['amount'] as num?)?.toDouble() ?? 0,
      status: json['status'] as String? ?? 'pending',
      dueDate: json['due_date'] != null
          ? DateTime.tryParse(json['due_date'].toString())
          : null,
    );
  }

  Map<String, dynamic> toJson() => {
        'title': title,
        'amount': amount,
        'status': status,
        'due_date': dueDate?.toIso8601String(),
      };
}

class Dispute {
  final int id;
  final int orderId;
  final String status;
  final List<DisputeMessage> messages;

  const Dispute({
    required this.id,
    required this.orderId,
    required this.status,
    this.messages = const [],
  });

  factory Dispute.fromJson(Map<String, dynamic> json) {
    return Dispute(
      id: json['id'] as int,
      orderId: json['order_id'] as int? ?? json['orderId'] as int,
      status: json['status'] as String? ?? 'open',
      messages: (json['messages'] as List? ?? [])
          .map((e) => DisputeMessage.fromJson(e as Map<String, dynamic>))
          .toList(),
    );
  }

  Map<String, dynamic> toJson() => {
        'id': id,
        'order_id': orderId,
        'status': status,
        'messages': messages.map((e) => e.toJson()).toList(),
      };
}

class DisputeMessage {
  final int id;
  final int userId;
  final String message;
  final DateTime? createdAt;

  const DisputeMessage({
    required this.id,
    required this.userId,
    required this.message,
    this.createdAt,
  });

  factory DisputeMessage.fromJson(Map<String, dynamic> json) {
    return DisputeMessage(
      id: json['id'] as int,
      userId: json['user_id'] as int? ?? json['userId'] as int,
      message: json['message'] as String? ?? '',
      createdAt: json['created_at'] != null
          ? DateTime.tryParse(json['created_at'].toString())
          : null,
    );
  }

  Map<String, dynamic> toJson() => {
        'id': id,
        'user_id': userId,
        'message': message,
        'created_at': createdAt?.toIso8601String(),
      };
}
