class InterviewSchedule {
  const InterviewSchedule({
    required this.id,
    required this.applicationId,
    required this.start,
    required this.end,
    this.location,
    this.link,
  });

  final int id;
  final int applicationId;
  final DateTime start;
  final DateTime end;
  final String? location;
  final String? link;

  factory InterviewSchedule.fromJson(Map<String, dynamic> json) =>
      InterviewSchedule(
        id: json['id'] as int,
        applicationId: json['application_id'] as int,
        start: DateTime.parse(json['start'] as String),
        end: DateTime.parse(json['end'] as String),
        location: json['location'] as String?,
        link: json['link'] as String?,
      );

  Map<String, dynamic> toJson() => {
        'id': id,
        'application_id': applicationId,
        'start': start.toIso8601String(),
        'end': end.toIso8601String(),
        'location': location,
        'link': link,
      };
}

class InterviewCandidate {
  const InterviewCandidate({required this.id, required this.name, required this.headline});

  final int id;
  final String name;
  final String headline;

  factory InterviewCandidate.fromJson(Map<String, dynamic> json) => InterviewCandidate(
        id: json['id'] as int,
        name: json['name'] as String? ?? 'Candidate',
        headline: json['headline'] as String? ?? '',
      );

  Map<String, dynamic> toJson() => {
        'id': id,
        'name': name,
        'headline': headline,
      };
}

class InterviewInvite {
  const InterviewInvite({
    required this.id,
    required this.title,
    required this.datetime,
    required this.candidate,
    required this.jobId,
    required this.status,
  });

  final int id;
  final String title;
  final String datetime;
  final InterviewCandidate candidate;
  final int jobId;
  final String status;

  factory InterviewInvite.fromJson(Map<String, dynamic> json) => InterviewInvite(
        id: json['id'] as int,
        title: json['title'] as String? ?? 'Interview',
        datetime: json['datetime'] as String? ?? '',
        candidate: InterviewCandidate.fromJson(json['candidate'] as Map<String, dynamic>),
        jobId: json['job_id'] as int? ?? 0,
        status: json['status'] as String? ?? 'scheduled',
      );

  Map<String, dynamic> toJson() => {
        'id': id,
        'title': title,
        'datetime': datetime,
        'candidate': candidate.toJson(),
        'job_id': jobId,
        'status': status,
      };
}
