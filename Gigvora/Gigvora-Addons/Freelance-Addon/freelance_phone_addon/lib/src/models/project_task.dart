class ProjectTask {
  const ProjectTask({
    required this.id,
    required this.title,
    required this.assignee,
    required this.status,
    required this.dueDate,
    required this.hoursLogged,
  });

  final int id;
  final String title;
  final String? assignee;
  final String status;
  final DateTime? dueDate;
  final double hoursLogged;

  factory ProjectTask.fromJson(Map<String, dynamic>? json) {
    final data = json ?? {};
    return ProjectTask(
      id: data['id'] is int ? data['id'] as int : int.tryParse('${data['id'] ?? 0}') ?? 0,
      title: data['title']?.toString() ?? '',
      assignee: data['assignee']?.toString(),
      status: data['status']?.toString() ?? 'pending',
      dueDate: data['due_date'] != null ? DateTime.tryParse(data['due_date'].toString()) : null,
      hoursLogged: (data['hours_logged'] as num?)?.toDouble() ?? 0,
    );
  }
}
