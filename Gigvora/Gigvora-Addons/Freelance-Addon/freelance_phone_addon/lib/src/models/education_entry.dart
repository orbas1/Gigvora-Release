import 'package:equatable/equatable.dart';

class EducationEntry extends Equatable {
  const EducationEntry({
    required this.id,
    required this.userId,
    required this.institution,
    this.degree,
    this.field,
    this.startYear,
    this.endYear,
  });

  final int id;
  final int userId;
  final String institution;
  final String? degree;
  final String? field;
  final int? startYear;
  final int? endYear;

  factory EducationEntry.fromJson(Map<String, dynamic>? json) {
    if (json == null) {
      return const EducationEntry(id: 0, userId: 0, institution: '');
    }

    int? _parseInt(dynamic value) => value is int ? value : int.tryParse(value?.toString() ?? '');

    return EducationEntry(
      id: json['id'] is int ? json['id'] : int.tryParse(json['id']?.toString() ?? '') ?? 0,
      userId: json['user_id'] is int ? json['user_id'] : int.tryParse(json['user_id']?.toString() ?? '') ?? 0,
      institution: json['institution']?.toString() ?? '',
      degree: json['degree']?.toString(),
      field: json['field']?.toString(),
      startYear: _parseInt(json['start_year']),
      endYear: _parseInt(json['end_year']),
    );
  }

  Map<String, dynamic> toJson() => {
        'id': id,
        'user_id': userId,
        'institution': institution,
        'degree': degree,
        'field': field,
        'start_year': startYear,
        'end_year': endYear,
      };

  @override
  List<Object?> get props => [id, userId, institution, degree, field, startYear, endYear];
}
