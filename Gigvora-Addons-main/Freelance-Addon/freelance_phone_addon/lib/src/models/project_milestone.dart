class ProjectMilestone {
  const ProjectMilestone({
    required this.id,
    required this.title,
    required this.amount,
    required this.status,
    required this.dueDate,
  });

  final int id;
  final String title;
  final double amount;
  final String status;
  final DateTime? dueDate;

  factory ProjectMilestone.fromJson(Map<String, dynamic>? json) {
    final data = json ?? {};
    return ProjectMilestone(
      id: data['id'] is int ? data['id'] as int : int.tryParse('${data['id'] ?? 0}') ?? 0,
      title: data['title']?.toString() ?? '',
      amount: (data['amount'] as num?)?.toDouble() ?? 0,
      status: data['status']?.toString() ?? 'pending',
      dueDate: data['due_date'] != null ? DateTime.tryParse(data['due_date'].toString()) : null,
    );
  }
}
