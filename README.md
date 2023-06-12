# feodorpranju/api-orm

Add ```git@github.com:feodorpranju/api-orm.git``` to your repository list in ```composer.json``` with type ```github```.
Install running ```composer require feodorpranju/api-orm```.

# Usage

- Model creation

  ```php
  <?php
  
  use Feodorpranju\ApiOrm\Models\AbstractModel;
  use \Illuminate\Support\Collection;
  use \Feodorpranju\ApiOrm\Models\Fields\Settings as FieldSettings;
  use \Feodorpranju\ApiOrm\Enumerations\FieldType;
  
  class Task extends AbstractModel
  {
      /**
      * @inheritdoc 
      */
      public static function fields() : Collection
      {
          return collect([
              new FieldSettings('id', FieldType::Int, false, true),
              new FieldSettings('title', FieldType::String),
              new FieldSettings('name', FieldType::String),
              new FieldSettings('date', FieldType::Datetime),
          ]);
      }
  }
  ```
  
    Set required methods e.g. get(), fields(), find(), count()...

- Model initialization

  ```php
  $task = Task::get(1);
  $task = Task::select()->first();
  $task = Task::where('id', 1)->first();
  $task = new Task(['id' => 1]);
  $task = Task::make(['id' => 1]);
  ```

- Model field usage

  ```php
  $task = Task::make([
      'id' => 1,
      'title' => 'Lorem ipsum'
  ]);
  
  # Field get
  echo $task->id;
  echo $task['ID'];
  
  # All fields
  $fields = $task->only();
  $fields = $task->except();
  
  # Needed fields
  $fields = $task->only(['id']);
  $fields = $task->except(['id']);
  
  # Field set
  $task->id = 2;
  $task['id'] = 2;
  $task->put([
      'id' => 2,
      'name' => 'dolor'
  ]);
  ```

- Select models

  ```php
  # Basic
  $tasks = Task::select()->all();
  $tasks = Task::select()->forPage(1, 10)->get();
  
  # With conditions
  $tasks = Task::select(['id', 'title'])->all();
  $tasks = Task::select()->where('id', 1)->all();
  $tasks = Task::where('id', '=', 1)->all();
  $tasks = Task::where('id', '!=', 1)->all();
  $tasks = Task::where('active')->all();
  $tasks = Task::where([
      'id' => 'Lorem',
      'title' => ['!=', 'ipsum'],
      ['name', 'dolor'],
      ['date', '<=', date('c')],
  ])->all();
  ```