class DisputeMessage {
  DisputeMessage({required this.author, required this.body, required this.time});
  final String author;
  final String body;
  final String time;

  factory DisputeMessage.fromJson(Map<String, dynamic> json) => DisputeMessage(
        author: json['author'] as String? ?? '',
        body: json['body'] as String? ?? '',
        time: json['time'] as String? ?? '',
      );
}

class Dispute {
  Dispute({
    required this.id,
    required this.title,
    required this.status,
    required this.reason,
    required this.contract,
    required this.messages,
  });

  final int id;
  final String title;
  final String status;
  final String reason;
  final String contract;
  final List<DisputeMessage> messages;

  factory Dispute.fromJson(Map<String, dynamic> json) => Dispute(
        id: json['id'] as int,
        title: json['title'] as String? ?? '',
        status: json['status'] as String? ?? 'open',
        reason: json['reason'] as String? ?? '',
        contract: json['contract'] as String? ?? '',
        messages: (json['messages'] as List<dynamic>? ?? [])
            .map((e) => DisputeMessage.fromJson(e as Map<String, dynamic>))
            .toList(),
      );

  Map<String, dynamic> toJson() => {
        'id': id,
        'title': title,
        'status': status,
        'reason': reason,
        'contract': contract,
        'messages': messages.map((e) => {
              'author': e.author,
              'body': e.body,
              'time': e.time,
            }).toList(),
      };
}
