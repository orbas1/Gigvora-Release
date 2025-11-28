import 'company.dart';
import 'screening.dart';

class Job {
  const Job({
    required this.id,
    required this.title,
    required this.description,
    required this.location,
    this.workplaceType,
    this.employmentType,
    this.salaryMin,
    this.salaryMax,
    this.currency,
    required this.salaryLabel,
    required this.company,
    required this.publishedAt,
    this.status,
    this.applicationsCount,
    this.tags = const <String>[],
    this.screeningQuestions = const <ScreeningQuestion>[],
  });

  final int id;
  final String title;
  final String description;
  final String location;
  final String? workplaceType;
  final String? employmentType;
  final double? salaryMin;
  final double? salaryMax;
  final String? currency;
  final String salaryLabel;
  final Company company;
  final DateTime publishedAt;
  final String? status;
  final int? applicationsCount;
  final List<String> tags;
  final List<ScreeningQuestion> screeningQuestions;

  factory Job.fromJson(Map<String, dynamic> json) => Job(
        id: json['id'] as int,
        title: json['title'] as String,
        description: json['description'] as String,
        location: json['location'] as String? ?? 'Remote',
        workplaceType: json['workplace_type'] as String?,
        employmentType: json['employment_type'] as String?,
        salaryMin: (json['salary_min'] as num?)?.toDouble(),
        salaryMax: (json['salary_max'] as num?)?.toDouble(),
        currency: json['currency'] as String?,
        salaryLabel: json['salary_label'] as String? ?? 'Competitive',
        company: Company.fromJson(json['company'] as Map<String, dynamic>),
        publishedAt: DateTime.parse(
            json['published_at'] as String? ?? DateTime.now().toIso8601String()),
        status: json['status'] as String?,
        applicationsCount: json['applications_count'] as int?,
        tags: (json['tag_list'] as List<dynamic>? ?? json['tags'] as List<dynamic>? ?? [])
            .cast<String>(),
        screeningQuestions: (json['screening_questions'] as List<dynamic>? ?? [])
            .map((e) => ScreeningQuestion.fromJson(e as Map<String, dynamic>))
            .toList(),
      );

  Map<String, dynamic> toJson() => {
        'id': id,
        'title': title,
        'description': description,
        'location': location,
        'workplace_type': workplaceType,
        'employment_type': employmentType,
        'salary_min': salaryMin,
        'salary_max': salaryMax,
        'currency': currency,
        'salary_label': salaryLabel,
        'company': company.toJson(),
        'published_at': publishedAt.toIso8601String(),
        'status': status,
        'applications_count': applicationsCount,
        'tags': tags,
        'screening_questions':
            screeningQuestions.map((question) => question.toJson()).toList(),
      };
}
