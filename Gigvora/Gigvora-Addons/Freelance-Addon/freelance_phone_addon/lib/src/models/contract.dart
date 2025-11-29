import 'milestone.dart';

class Contract {
  Contract({
    required this.id,
    required this.title,
    required this.amount,
    required this.status,
    required this.counterpart,
    required this.milestones,
  });

  final int id;
  final String title;
  final double amount;
  final String status;
  final String counterpart;
  final List<Milestone> milestones;

  factory Contract.fromJson(Map<String, dynamic> json) => Contract(
        id: json['id'] as int,
        title: json['title'] as String,
        amount: (json['amount'] as num).toDouble(),
        status: json['status'] as String? ?? 'active',
        counterpart: json['counterpart'] as String? ?? '',
        milestones: (json['milestones'] as List<dynamic>? ?? [])
            .map((e) => Milestone.fromJson(e as Map<String, dynamic>))
            .toList(),
      );

  Map<String, dynamic> toJson() => {
        'id': id,
        'title': title,
        'amount': amount,
        'status': status,
        'counterpart': counterpart,
        'milestones': milestones.map((e) => e.toJson()).toList(),
      };
}
