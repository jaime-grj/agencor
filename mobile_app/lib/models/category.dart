class Category {
  final int id;
  final String name;
  final dynamic description;

  Category({
    required this.id,
    required this.name,
    this.description,
  });

  factory Category.fromJson(Map<String, dynamic> json) => Category(
        id: json["id"],
        name: json["name"],
        description: json["description"],
      );

  Map<String, dynamic> toJson() => {
        "id": id,
        "name": name,
        "description": description?.toString(),
      };

  Map<String, String> toMap() {
    return {
      'id': id.toString(),
      'name': name,
    };
  }
}
