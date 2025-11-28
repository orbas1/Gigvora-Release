class Proposal {
  Proposal({
    required this.id,
    required this.projectId,
    required this.amount,
    required this.duration,
    required this.coverLetter,
    required this.status,
  });

  final int id;
  final int projectId;
  final double amount;
  final int duration;
  final String coverLetter;
  final String status;

  factory Proposal.fromJson(Map<String, dynamic> json) => Proposal(
        id: json['id'] as int,
        projectId: json['project_id'] as int,
        amount: (json['amount'] as num).toDouble(),
        duration: json['duration'] as int? ?? 0,
        coverLetter: json['cover_letter'] as String? ?? '',
        status: json['status'] as String? ?? 'pending',
      );

  Map<String, dynamic> toJson() => {
        'id': id,
        'project_id': projectId,
        'amount': amount,
        'duration': duration,
        'cover_letter': coverLetter,
        'status': status,
      };
}
