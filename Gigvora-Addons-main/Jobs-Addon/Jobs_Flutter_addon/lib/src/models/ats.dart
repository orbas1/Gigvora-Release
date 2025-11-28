class AtsStage {
  const AtsStage({required this.id, required this.name, required this.order});

  final int id;
  final String name;
  final int order;

  factory AtsStage.fromJson(Map<String, dynamic> json) => AtsStage(
        id: json['id'] as int,
        name: json['name'] as String,
        order: json['order'] as int? ?? json['position'] as int? ?? 0,
      );

  Map<String, dynamic> toJson() => {
        'id': id,
        'name': name,
        'order': order,
      };
}

class AtsPipeline {
  const AtsPipeline({required this.id, required this.stages});

  final int id;
  final List<AtsStage> stages;

  factory AtsPipeline.fromJson(Map<String, dynamic> json) => AtsPipeline(
        id: json['id'] as int,
        stages: (json['stages'] as List<dynamic>? ?? [])
            .map((e) => AtsStage.fromJson(e as Map<String, dynamic>))
            .toList(),
      );

  Map<String, dynamic> toJson() => {
        'id': id,
        'stages': stages.map((stage) => stage.toJson()).toList(),
      };
}
