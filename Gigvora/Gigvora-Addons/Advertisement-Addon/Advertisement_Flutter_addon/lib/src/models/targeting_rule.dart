import 'package:equatable/equatable.dart';

class TargetingRule extends Equatable {
  const TargetingRule({
    required this.id,
    required this.adGroupId,
    required this.type,
    required this.value,
  });

  factory TargetingRule.fromJson(Map<String, dynamic> json) {
    return TargetingRule(
      id: json['id'] as int,
      adGroupId: json['ad_group_id'] as int,
      type: json['type'] as String,
      value: json['value'] as String,
    );
  }

  final int id;
  final int adGroupId;
  final String type;
  final String value;

  Map<String, dynamic> toJson() => {
        'id': id,
        'ad_group_id': adGroupId,
        'type': type,
        'value': value,
      };

  @override
  List<Object?> get props => [id, adGroupId, type, value];
}
