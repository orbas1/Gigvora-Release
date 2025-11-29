import 'package:equatable/equatable.dart';

class FreelanceTag extends Equatable {
  const FreelanceTag({
    required this.id,
    required this.name,
    this.type,
  });

  final int id;
  final String name;
  final String? type;

  factory FreelanceTag.fromJson(Map<String, dynamic>? json) {
    if (json == null) {
      return const FreelanceTag(id: 0, name: '');
    }
    return FreelanceTag(
      id: json['id'] is int ? json['id'] : int.tryParse(json['id']?.toString() ?? '') ?? 0,
      name: json['name']?.toString() ?? '',
      type: json['type']?.toString(),
    );
  }

  Map<String, dynamic> toJson() => {
        'id': id,
        'name': name,
        'type': type,
      };

  @override
  List<Object?> get props => [id, name, type];
}
