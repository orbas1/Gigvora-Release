import 'package:equatable/equatable.dart';

class Placement extends Equatable {
  const Placement({
    required this.id,
    required this.adGroupId,
    required this.type,
    required this.slot,
  });

  factory Placement.fromJson(Map<String, dynamic> json) {
    return Placement(
      id: json['id'] as int,
      adGroupId: json['ad_group_id'] as int,
      type: json['type'] as String,
      slot: json['slot'] as String,
    );
  }

  final int id;
  final int adGroupId;
  final String type;
  final String slot;

  Map<String, dynamic> toJson() => {
        'id': id,
        'ad_group_id': adGroupId,
        'type': type,
        'slot': slot,
      };

  @override
  List<Object?> get props => [id, adGroupId, type, slot];
}
